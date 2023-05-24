<template>
  <div class="audio">
    <button
      title="play"
      v-if="!isStartSpeak"
      class="speak btn btn-hero"
      @click="play()"
      style="outline:none;border-radius: 50%;"
    >
      <svg
        style="width:30px"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g
          id="SVGRepo_tracerCarrier"
          stroke-linecap="round"
          stroke-linejoin="round"
        ></g>
        <g id="SVGRepo_iconCarrier">
          <path
            opacity="0.1"
            d="M4 5.49683V18.5032C4 20.05 5.68077 21.0113 7.01404 20.227L18.0694 13.7239C19.384 12.9506 19.384 11.0494 18.0694 10.2761L7.01404 3.77296C5.68077 2.98869 4 3.95 4 5.49683Z"
            fill="#ffffff"
          ></path>
          <path
            d="M4 5.49683V18.5032C4 20.05 5.68077 21.0113 7.01404 20.227L18.0694 13.7239C19.384 12.9506 19.384 11.0494 18.0694 10.2761L7.01404 3.77296C5.68077 2.98869 4 3.95 4 5.49683Z"
            stroke="#ffffff"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></path>
        </g>
      </svg>
    </button>
    <div v-else>
      <button
        title="pause"
        v-if="!isResume"
        class="speak btn btn-hero"
        @click="pause()"
        style="outline:none;border-radius: 50%;"
      >
        <svg
          style="width: 30px;"
          fill="#ffffff"
          viewBox="0 0 32 32"
          xmlns="http://www.w3.org/2000/svg"
          stroke="#ffffff"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <path
              d="M18.432 7.5h4.547v17h-4.547zM9.022 7.5h4.545v17H9.022z"
            ></path>
          </g>
        </svg>
      </button>
      <button
        title="résumé"
        v-else
        class="speak btn btn-hero"
        @click="resume()"
        style="outline:none;border-radius: 50%;"
      >
        <svg
          style="width: 30px;"
          viewBox="0 0 24 24"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <path
              opacity="0.1"
              d="M4 5.49683V18.5032C4 20.05 5.68077 21.0113 7.01404 20.227L18.0694 13.7239C19.384 12.9506 19.384 11.0494 18.0694 10.2761L7.01404 3.77296C5.68077 2.98869 4 3.95 4 5.49683Z"
              fill="#ffffff"
            ></path>
            <path
              d="M4 5.49683V18.5032C4 20.05 5.68077 21.0113 7.01404 20.227L18.0694 13.7239C19.384 12.9506 19.384 11.0494 18.0694 10.2761L7.01404 3.77296C5.68077 2.98869 4 3.95 4 5.49683Z"
              stroke="#ffffff"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></path>
          </g>
        </svg>
      </button>
      <button
        title="finir la lecture"
        class="speak btn btn-hero"
        @click="stop()"
        style="outline:none;border-radius: 50%;"
      >
        <svg
          viewBox="0 0 192 192"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
        >
          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
          <g
            id="SVGRepo_tracerCarrier"
            stroke-linecap="round"
            stroke-linejoin="round"
          ></g>
          <g id="SVGRepo_iconCarrier">
            <path
              stroke="#ffffff"
              stroke-width="12"
              d="M168.356 92.58 48.073 19.68C45.407 18.066 42 19.985 42 23.103v30.645a4 4 0 0 0 1.927 3.42l58.429 35.412c2.569 1.557 2.569 5.285 0 6.842l-58.43 35.411a4 4 0 0 0-1.926 3.42v30.645c0 3.118 3.407 5.037 6.073 3.421l120.283-72.898c2.569-1.557 2.569-5.285 0-6.842Z"
            ></path>
            <path
              fill="#ffffff"
              d="M36 113.449V78.551c0-1.559 1.704-2.518 3.037-1.71l28.79 17.449c1.285.778 1.285 2.642 0 3.42l-28.79 17.45c-1.333.807-3.037-.152-3.037-1.711Z"
            ></path>
          </g>
        </svg>
      </button>
      <!-- <span
                style="min-width: 50px; display: inline-block; text-align: center;color:black"
                >{{ timer_val }}</span
              > -->
      <input
        style="display: inline;width: fit-content;"
        class="form-control-range"
        v-model="sendSpeed"
        type="range"
        min="0.5"
        max="3"
        step="0.5"
        @change="changeTextReaderSpeed"
      />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isStartSpeak: false,
      isResume: false,
      sendSpeed: 0.5,
      lineHeight: 0,
      utterance: new SpeechSynthesisUtterance(),
      tts: window.speechSynthesis,
    };
  },
  mounted() {
    this.utterance.rate = 1;
    this.utterance.lang = "en-UK";
  },
  methods: {
    play() {
      speechSynthesis.cancel();

      this.isStartSpeak = true;
      this.isResume = false;
      var text = document.getElementById("textarea").value;
      const speak = new SpeechSynthesisUtterance();
      speak.rate = this.sendSpeed;
      speak.onboundary = this.onboundaryHandler;
      speak.text = text;
      // speak.volume = 0;
      let voice = this.tts.getVoices();
      speak.voice = voice[0];
      speechSynthesis.speak(speak);
    },

    pause() {
      if (speechSynthesis) {
        speechSynthesis.pause();
        this.isResume = true;
      }
    },
    stop() {
      if (speechSynthesis) {
        speechSynthesis.cancel();
        this.isStartSpeak = false;
        swal.fire("Finir la lecture", "", "success");
      }
    },
    resume() {
      if (speechSynthesis) {
        speechSynthesis.resume();
        this.isResume = false;
      }
    },

    onboundaryHandler(event) {
      var textarea = document.getElementById("textarea");
      var value = textarea.value;
      var index = event.charIndex;
      var word = this.getWordAt(value, index);
      var anchorPosition = this.getWordStart(value, index);
      var activePosition = anchorPosition + word.length;

      textarea.focus();
      if (textarea.setSelectionRange) {
        textarea.setSelectionRange(anchorPosition, activePosition);
      } else {
        var range = textarea.createTextRange();
        range.collapse(true);
        range.moveEnd("character", activePosition);
        range.moveStart("character", anchorPosition);
        range.select();
      }
      var charsPerRow = textarea.cols;
      // We need to know at which row our selection starts

      var selectionRow =
        (activePosition - (activePosition % charsPerRow)) / charsPerRow;

      // We need to scroll to this row but scrolls are in pixels,
      // so we need to know a row's height, in pixels
      var lineHeight = textarea.clientHeight / textarea.rows;
      // textarea.scrollTop = (lineHeight * selectionRow) / 3.7;
      console.log(selectionRow);
    },
    getWordAt(str, pos) {
      str = String(str);
      pos = Number(pos) >>> 0;

      var left = str.slice(0, pos + 1).search(/\S+$/),
        right = str.slice(pos).search(/\s/);

      if (right < 0) {
        return str.slice(left);
      }

      return str.slice(left, right + pos);
    },

    getWordStart(str, pos) {
      str = String(str);
      pos = Number(pos) >>> 0;

      // Search for the word's beginning
      var start = str.slice(0, pos + 1).search(/\S+$/);
      return start;
    },
    changeTextReaderSpeed() {
      speechSynthesis.cancel();
      var text = document.getElementById("textarea").value;
      const speak = new SpeechSynthesisUtterance();
      speak.rate = this.sendSpeed;
      speak.onboundary = this.onboundaryHandler;
      speak.text = text;
      let voice = this.tts.getVoices();
      speak.voice = voice[0];
      speechSynthesis.speak(speak);
    },
  },
};
</script>

<style></style>
