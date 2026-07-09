<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  adminPasscode: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const editorContainer = ref(null);
const imageInput = ref(null);
const uploading = ref(false);
const uploadError = ref('');
let quill = null;
let isUpdatingFromOutside = false;

const toolbarOptions = [
  [{ header: [2, 3, 4, false] }],
  ['bold', 'italic', 'underline', 'strike'],
  [{ color: [] }, { background: [] }],
  [{ list: 'ordered' }, { list: 'bullet' }],
  [{ indent: '-1' }, { indent: '+1' }],
  ['blockquote', 'code-block'],
  ['link', 'image'],
  ['clean']
];

// Custom image upload handler
const handleImageUpload = () => {
  imageInput.value.click();
};

const onFileSelected = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  // Reset input so same file can be re-selected
  event.target.value = '';

  uploading.value = true;
  uploadError.value = '';

  try {
    const formData = new FormData();
    formData.append('image', file);

    const res = await fetch('/api/admin/upload-image', {
      method: 'POST',
      headers: {
        'X-Admin-Passcode': props.adminPasscode
      },
      body: formData
    });

    const data = await res.json();

    if (res.ok && data.success) {
      // Insert image into editor at current cursor position
      const range = quill.getSelection(true);
      quill.insertEmbed(range.index, 'image', data.url);
      quill.setSelection(range.index + 1);
    } else {
      uploadError.value = data.message || 'Upload thất bại';
      setTimeout(() => { uploadError.value = ''; }, 4000);
    }
  } catch (err) {
    uploadError.value = 'Lỗi kết nối, thử lại.';
    setTimeout(() => { uploadError.value = ''; }, 4000);
  } finally {
    uploading.value = false;
  }
};

onMounted(async () => {
  await nextTick();

  quill = new Quill(editorContainer.value, {
    theme: 'snow',
    placeholder: 'Viết nội dung bài viết tại đây...',
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: handleImageUpload
        }
      }
    }
  });

  // Load initial content
  if (props.modelValue && props.modelValue.trim()) {
    isUpdatingFromOutside = true;
    quill.clipboard.dangerouslyPasteHTML(props.modelValue);
    isUpdatingFromOutside = false;
  }

  // Emit on change
  quill.on('text-change', () => {
    if (isUpdatingFromOutside) return;
    const html = quill.root.innerHTML;
    emit('update:modelValue', html === '<p><br></p>' ? '' : html);
  });
});

// Watch for external value changes
watch(() => props.modelValue, (newVal) => {
  if (!quill) return;
  const currentHtml = quill.root.innerHTML;
  const incoming = newVal || '';
  if (currentHtml !== incoming) {
    isUpdatingFromOutside = true;
    if (incoming.trim()) {
      quill.clipboard.dangerouslyPasteHTML(incoming);
    } else {
      quill.setContents([]);
    }
    isUpdatingFromOutside = false;
  }
});

onBeforeUnmount(() => {
  quill = null;
});
</script>

<template>
  <div class="rich-editor-wrapper">
    <!-- Hidden file input for image upload -->
    <input
      ref="imageInput"
      type="file"
      accept="image/jpeg,image/png,image/gif,image/webp"
      class="hidden"
      @change="onFileSelected"
    />

    <!-- Upload status -->
    <div v-if="uploading" class="flex items-center gap-2 mb-2 text-xs text-blue-600 dark:text-blue-400 font-semibold">
      <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      Đang tải ảnh lên...
    </div>
    <div v-if="uploadError" class="mb-2 text-xs text-rose-500 font-semibold">
      ⚠ {{ uploadError }}
    </div>

    <div ref="editorContainer"></div>
  </div>
</template>

<style scoped>
.hidden { display: none; }

.rich-editor-wrapper :deep(.ql-toolbar) {
  border-top-left-radius: 0.75rem;
  border-top-right-radius: 0.75rem;
  border-color: rgb(148 163 184 / 0.4);
  background: rgb(248 250 252);
  flex-wrap: wrap;
}

.rich-editor-wrapper :deep(.ql-container) {
  border-bottom-left-radius: 0.75rem;
  border-bottom-right-radius: 0.75rem;
  border-color: rgb(148 163 184 / 0.4);
  font-size: 0.9rem;
  font-family: inherit;
}

.rich-editor-wrapper :deep(.ql-editor) {
  min-height: 260px;
  max-height: 480px;
  overflow-y: auto;
  color: #1e293b;
  background: white;
  border-bottom-left-radius: 0.75rem;
  border-bottom-right-radius: 0.75rem;
  line-height: 1.75;
}

.rich-editor-wrapper :deep(.ql-editor.ql-blank::before) {
  color: rgb(148 163 184);
  font-style: normal;
}

.rich-editor-wrapper :deep(.ql-editor img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 0.5rem 0;
}

/* Dark mode */
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar) {
  background: #0c1524;
  border-color: rgb(51 65 85 / 0.8);
}
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar .ql-stroke) {
  stroke: #94a3b8;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar .ql-fill) {
  fill: #94a3b8;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar .ql-picker-label) {
  color: #94a3b8;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar button:hover .ql-stroke),
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar button.ql-active .ql-stroke) {
  stroke: #60a5fa;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar button:hover .ql-fill),
:global(.dark) .rich-editor-wrapper :deep(.ql-toolbar button.ql-active .ql-fill) {
  fill: #60a5fa;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-container) {
  border-color: rgb(51 65 85 / 0.8);
}
:global(.dark) .rich-editor-wrapper :deep(.ql-editor) {
  background: #060b13;
  color: #e2e8f0;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-picker-options) {
  background: #0c1524;
  border-color: #334155;
  color: #e2e8f0;
}
:global(.dark) .rich-editor-wrapper :deep(.ql-picker-item) {
  color: #e2e8f0;
}
</style>
