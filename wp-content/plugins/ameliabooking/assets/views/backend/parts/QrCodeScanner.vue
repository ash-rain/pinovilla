<template>
  <!-- QR Code Scanner Dialog -->
  <el-dialog
    :visible.sync="qrScannerVisible"
    :title="$root.labels.e_ticket_scanner"
    width="80%"
    :before-close="closeQrScanner"
    class="am-qr-scanner__dialog"
  >
    <div v-if="qrScannerVisible" class="am-qr-scanner__container">
      <div
        v-show="!dataLoading"
        class="am-qr-scanner__camera-container"
      >
        <video
          ref="videoElement"
          class="am-qr-scanner__video"
          :style="{display: isScanning ? 'block' : 'none'}"
          autoplay
          playsinline
        ></video>
        <canvas
          ref="canvasElement"
          class="am-qr-scanner__canvas"
          style="display: none;"
        ></canvas>

        <!-- Scanner overlay -->
        <div class="am-qr-scanner__video-overlay">
          <div class="am-qr-scanner__frame"></div>
        </div>
      </div>

      <div
        v-if="responseData && !dataLoading"
        class="am-qr-scanner__response-info"
      >
        <i :class="responseData.messageType === 'error' ? 'el-icon-warning' : 'el-icon-success'"></i>
        <div v-if="responseData.message" class="am-qr-scanner__response-message">
          {{ $root.labels[responseData.message] || responseData.message }}
        </div>
        <div v-if="responseData.eventName" class="am-qr-scanner__response-name">
          {{ responseData.eventName }}
        </div>
        <div v-if="responseData.ticketManualCode" class="am-qr-scanner__response-item">
          <span>{{$root.labels.ticket_id}}:</span> #{{ responseData.ticketManualCode }}
        </div>
        <div v-if="responseData.eventTicketName" class="am-qr-scanner__response-item">
          <span>{{$root.labels.ticket_name}}:</span> {{ responseData.eventTicketName }}
        </div>
        <div v-if="responseData.bookingId" class="am-qr-scanner__response-item">
          <span>{{$root.labels.booking_id}}:</span> #{{ responseData.bookingId }}
        </div>
        <div v-if="responseData.ticketControlNumber" class="am-qr-scanner__response-item">
          <span>{{$root.labels.attendees_allowed}}:</span> {{ responseData.ticketControlNumber }}
        </div>
      </div>

      <div
        v-if="scanError && !dataLoading"
        class="am-qr-scanner__error"
      >
        <i class="el-icon-warning"></i>
        {{ scanError }}
      </div>

      <div
        v-show="!dataLoading"
        class="am-qr-scanner__controls"
      >
        <el-button @click="toggleTorch" type="primary" v-if="torchSupported">
          {{ torchActive ? $root.labels.torch_off : $root.labels.torch_on }}
        </el-button>

        <el-button @click="switchCamera" v-if="cameras.length > 1">
          <i class="el-icon-refresh"></i>
          {{ $root.labels.switch_camera }}
        </el-button>

        <el-button @click="startScanning" v-if="!isScanning" type="primary">
          <i class="el-icon-video-camera"></i>
          {{ $root.labels.start_scanner }}
        </el-button>

        <el-button @click="stopScanning" v-if="isScanning" type="danger">
          <i class="el-icon-video-camera-solid"></i>
          {{ $root.labels.stop_scanner }}
        </el-button>
      </div>

      <!-- Manual form -->
      <el-divider v-if="!dataLoading" />
      <div
        v-show="!dataLoading"
        class="am-qr-scanner__manual-input"
      >
        <el-collapse v-model="accordionActiveNames" accordion>
          <el-collapse-item
            name="1"
            :title="$root.labels.enter_ticket_manually"
            class="am-qr-scanner__collapse"
          >
            <el-form
              :model="manualCode"
              :rules="rules"
              ref="manualCodeForm"
              @submit.prevent="processManualInput"
            >
              <el-form-item prop="ticketCode">
                <el-input
                  v-model="manualCode.ticketCode"
                  :placeholder="$root.labels.enter_ticket_code"
                >
                  <span slot="prepend">#</span>
                </el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click="processManualInput">
                  {{$root.labels.validate_ticket}}
                </el-button>
              </el-form-item>
            </el-form>
          </el-collapse-item>
        </el-collapse>
      </div>

      <div class="am-dialog-loader" v-if="dataLoading" style="position: relative">
        <div class="am-dialog-loader-content">
          <img :src="$root.getUrl + 'public/img/spinner.svg'" class=""/>
          <p>{{ $root.labels.loader_message }}</p>
        </div>
      </div>
    </div>
  </el-dialog>
</template>

<script>

import jsQR from 'jsqr'
import moment from 'moment'

export default {
  name: 'QrCodeScanner',

  props: {
    visibility: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      dataLoading: false,
      isScanning: false,
      torchActive: false,
      torchSupported: false,
      cameras: [],
      currentCameraIndex: 0,
      scanError: '',
      stream: null,
      // Added for jsQR loop
      frameRequestId: null,
      lastDecodedAt: 0,
      decodeCooldownMs: 1500,
      // Manual form for ticket code input
      manualCode: {
        ticketCode: '',
        type: 'ticket'
      },
      rules: {
        ticketCode: [{ required: true, message: 'Ticket code is required', trigger: 'click' }]
      },
      accordionActiveNames: '',
      // Response data from backend
      responseData: null
    }
  },

  computed: {
    qrScannerVisible () {
      return this.visibility
    }
  },

  methods: {
    async initializeCamera () {
      try {
        // Check if we're on HTTPS, localhost, or 127.0.0.1
        const isSecureContext = window.isSecureContext ||
          location.protocol === 'https:' ||
          location.hostname === 'localhost' ||
          location.hostname === '127.0.0.1' ||
          location.hostname.includes('ngrok.io') ||
          location.hostname.startsWith('192.168.') ||
          location.hostname.startsWith('10.') ||
          location.hostname.startsWith('172.')

        if (!isSecureContext) {
          throw new Error('Camera access requires HTTPS or localhost. Please access this page over a secure connection or use localhost.')
        }

        // Check if MediaDevices API is supported
        if (!navigator.mediaDevices) {
          throw new Error('MediaDevices API not supported in this browser. Please update your browser or try Chrome/Firefox.')
        }

        if (!navigator.mediaDevices.getUserMedia) {
          throw new Error('getUserMedia not supported in this browser. Please update your browser or try Chrome/Firefox.')
        }

        // Try to get available cameras with permission check
        let devices = []
        try {
          devices = await navigator.mediaDevices.enumerateDevices()
        } catch (enumError) {
          console.warn('Could not enumerate devices:', enumError)
          // Try to proceed without device enumeration
        }

        this.cameras = devices.filter(device => device.kind === 'videoinput')

        // Even if we can't enumerate devices, try to start scanning
        // The browser will handle camera selection
        await this.startScanning()
      } catch (error) {
        this.handleScannerError(error)
      }
    },

    async startScanning () {
      this.responseData = null
      try {
        this.scanError = ''

        // Check MediaDevices support again
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          throw new Error('Camera access not supported in this browser')
        }

        // Stop any existing stream
        if (this.stream) {
          this.stream.getTracks().forEach(track => track.stop())
        }

        // Start with basic constraints
        let constraints = {
          video: {
            facingMode: 'environment' // Prefer back camera
          }
        }

        // If we have specific camera selected, use it
        if (this.cameras.length > 0 && this.cameras[this.currentCameraIndex] && this.cameras[this.currentCameraIndex].deviceId) {
          constraints.video.deviceId = { exact: this.cameras[this.currentCameraIndex].deviceId }
        } else {
          // Try with additional constraints for better quality
          constraints.video = {
            facingMode: 'environment',
            width: { ideal: 1280, min: 640 },
            height: { ideal: 720, min: 480 }
          }
        }

        // Get media stream
        this.stream = await navigator.mediaDevices.getUserMedia(constraints)

        // Check torch support
        const track = this.stream.getVideoTracks()[0]
        if (track && track.getCapabilities) {
          const capabilities = track.getCapabilities()
          this.torchSupported = !!capabilities.torch
        }

        // Attach stream to video element
        if (this.$refs.videoElement) {
          this.$refs.videoElement.srcObject = this.stream

          // Wait for video to load
          this.$refs.videoElement.onloadedmetadata = () => {
            this.$refs.videoElement.play().catch(e => {
              console.warn('Could not auto-play video:', e)
            })
          }
        }

        this.isScanning = true

        // Start QR code detection (simplified version)
        this.startQRDetection()
      } catch (error) {
        this.handleScannerError(error)
      }
    },

    stopScanning () {
      this.responseData = null
      this.isScanning = false
      if (this.frameRequestId) {
        cancelAnimationFrame(this.frameRequestId)
        this.frameRequestId = null
      }
      if (this.stream) {
        this.stream.getTracks().forEach(t => t.stop())
        this.stream = null
      }
      if (this.$refs.videoElement) this.$refs.videoElement.srcObject = null
    },

    startQRDetection () {
      const video = this.$refs.videoElement
      const canvas = this.$refs.canvasElement
      if (!video || !canvas) return
      const ctx = canvas.getContext('2d', { willReadFrequently: true })

      const scan = () => {
        if (!this.isScanning) return
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
          if (canvas.width !== video.videoWidth || canvas.height !== video.videoHeight) {
            canvas.width = video.videoWidth
            canvas.height = video.videoHeight
          }
          ctx.drawImage(video, 0, 0, canvas.width, canvas.height)
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)

          const now = performance.now()
          if (now - this.lastDecodedAt >= this.decodeCooldownMs) {
            const qr = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' })
            if (qr && qr.data) {
              if (qr.data) {
                this.onTicketScan(qr.data)
                this.stopScanning()
              }
              this.lastDecodedAt = now
            }
          }
        }
        this.frameRequestId = requestAnimationFrame(scan)
      }

      this.frameRequestId = requestAnimationFrame(scan)
    },

    async toggleTorch () {
      if (!this.torchSupported || !this.stream) return

      try {
        const track = this.stream.getVideoTracks()[0]
        await track.applyConstraints({
          advanced: [{ torch: !this.torchActive }]
        })
        this.torchActive = !this.torchActive
      } catch (error) {
        console.error('Error toggling torch:', error)
      }
    },

    async switchCamera () {
      if (this.cameras.length <= 1) return

      this.currentCameraIndex = (this.currentCameraIndex + 1) % this.cameras.length
      await this.startScanning()
    },

    processManualInput () {
      this.$refs.manualCodeForm.validate((valid) => {
        if (valid) {
          const ticketData = {
            ticketManualCode: this.manualCode.ticketCode.trim(),
            type: this.manualCode.type,
            scannedAt: moment().format('YYYY-MM-DD')
          }
          this.sendTicketData(ticketData)
          // Clear form
          this.manualCode.ticketCode = ''
        } else {
          return false
        }
      })
    },

    parseQrCodeTicketString (string) {
      const obj = {}
      const regex = /(\w+)\s*:\s*([^\s|]+)/g
      let m
      while ((m = regex.exec(string)) !== null) {
        let key = m[1]
        let value = m[2]
        if (/^-?\d+$/.test(value)) {
          value = Number(value)
        }
        obj[key] = value
      }
      return obj
    },

    onTicketScan (ticketCode) {
      this.scanError = ''
      let ticketDataResult = this.parseQrCodeTicketString(ticketCode)
      const scannedAt = moment().format('YYYY-MM-DD')

      let ticketData = {
        ...ticketDataResult,
        scannedAt
      }

      this.sendTicketData(ticketData)
    },

    sendTicketData (ticketData) {
      this.dataLoading = true
      this.scanError = ''
      // Call the backend API to validate and check in the ticket
      this.$http.post(`${this.$root.getAjaxUrl}/scan-eticket`, {
        ticketManualCode: ticketData.ticketManualCode,
        scannedAt: ticketData.scannedAt
      })
        .then(response => {
          if (response.data && response.data.data) {
            this.responseData = {
              messageType: response.data.data.messageType,
              message: response.data.data.message,
              bookingId: response.data.data.bookingId,
              ticketManualCode: response.data.data.ticketManualCode,
              ticketControlNumber: response.data.data.ticketControl,
              eventName: response.data.data.eventName ? response.data.data.eventName : this.getResponseEventName(response.data.data)
            }

            if (this.getResponseEventTicketName(response.data.data)) {
              this.responseData.eventTicketName = this.getResponseEventTicketName(response.data.data)
            }
          }
        })
        .catch(error => {
          const resp = error.response && error.response.data

          if (!resp) return

          if (error.response.data.data) {
            this.responseData = {
              messageType: error.response.data.data.messageType,
              message: error.response.data.data.message
            }
          } else {
            this.responseData = {
              messageType: 'error',
              message: error.response.data.message
            }
            if (error.response.data.message.includes('Mandatory fields not passed!')) {
              this.responseData.message = this.$root.labels.ticket_not_valid
            }
          }
        })
        .finally(() => {
          this.dataLoading = false
        })
    },

    getResponseEventName (data) {
      let qrItem = data.qrCodes.find(qr => qr.ticketManualCode === data.ticketManualCode)

      return qrItem.eventName || ''
    },

    getResponseEventTicketName (data) {
      let qrItem = data.qrCodes.find(qr => qr.ticketManualCode === data.ticketManualCode)

      return qrItem.eventTicketName || ''
    },

    onInit (promise) {
      promise.catch(error => {
        this.handleScannerError(error)
      })
    },

    handleScannerError (error) {
      console.error('Scanner error:', error)

      if (error.name === 'NotAllowedError') {
        this.scanError = this.$root.labels.camera_error_1
      } else if (error.name === 'NotFoundError') {
        this.scanError = this.$root.labels.camera_error_2
      } else if (error.name === 'NotSupportedError') {
        this.scanError = this.$root.labels.camera_error_3
      } else {
        this.scanError = `Camera error: ${error.message}`
      }
    },

    closeQrScanner () {
      this.stopScanning()
      this.$emit('close-scanner')
    }
  },

  mounted () {
    if (this.qrScannerVisible) {
      this.$nextTick(() => {
        this.initializeCamera()
      })
    }
  },

  beforeDestroy () {
    this.stopScanning()
  }
}
</script>

<style lang="less">
@import '../../../less/common/_variables';

.am-qr-scanner__dialog {
  .el-dialog {
    max-width: 620px;
    width: 100%;

    .am-qr-scanner__container {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;

      * {
        word-break: break-word;
      }

      .el-divider {
        margin: 0;
      }

      .el-form {
        width: 100%;
        margin-top: 12px;
      }

      .el-form-item {
        .el-button {
          width: 100%;
        }
      }

      .el-select {
        width: 100%;
      }
    }
  }
}

.am-qr-scanner__camera-container {
  position: relative;
  width: 100%;
  max-width: 500px;
  background: #000;
  border-radius: 8px;
  overflow: hidden;
}

.am-qr-scanner__video {
  width: 100%;
  height: auto;
  display: block;
}

.am-qr-scanner__video-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
}

.am-qr-scanner__frame {
  width: 200px;
  height: 200px;
  border: 3px solid #1A84EE;
  border-radius: 8px;
  background: transparent;
  position: relative;
}

.am-qr-scanner__frame::before,
.am-qr-scanner__frame::after {
  content: '';
  position: absolute;
  width: 20px;
  height: 20px;
  border: 3px solid #1A84EE;
}

.am-qr-scanner__frame::before {
  top: -10px;
  left: -10px;
  border-right: none;
  border-bottom: none;
  border-radius: 10px 0 0 0;
}
.am-qr-scanner__frame::after {
  bottom: -10px;
  right: -10px;
  border-left: none;
  border-top: none;
  border-radius: 0 0 10px 0;
}

.am-qr-scanner__controls {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: center;
}

.am-qr-scanner__error {
  color: #f56c6c;
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background-color: #fef0f0;
  border-radius: 4px;
  border: 1px solid #fbc4c4;
  text-align: center;
  max-width: 500px;
}

.am-qr-scanner__manual-input {
  width: 100%;
  max-width: 320px;
}

.am-qr-scanner-dialog .el-dialog__body {
  padding: 20px;
}

.am-qr-scanner__response-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;

  .el-icon {
    &-success {
      color: #67C23A;
      font-size: 38px;
    }
    &-warning {
      color: #F56C6C;
      font-size: 38px;
    }
  }
}

.am-qr-scanner__response-message {
  font-weight: bold;
  font-size: 26px;
  line-height: 1.5;
  text-align: center;
}

.am-qr-scanner__response-name {
  font-size: 18px;
  font-weight: bold;
  text-align: center;
  margin-top: 16px;
  margin-bottom: 8px;
}

.am-qr-scanner__response-item {
  font-size: 14px;
  font-weight: bold;

  span {
    font-weight: normal;
    color: @color-text-second;
  }
}

@media (max-width: 768px) {
  .am-qr-scanner {
    &__camera-container {
      max-width: 100%;
    }

    &__controls {
      flex-direction: column;
      align-items: center;

      .el-button {
        width: 200px;
      }
    }
  }
}
</style>
