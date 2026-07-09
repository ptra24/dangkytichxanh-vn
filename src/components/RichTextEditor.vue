<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const editorContainer = ref(null);
let quill = null;
let isUpdatingFromOutside = false;

const toolbarOptions = [
  [{ header: [2, 3, 4, false] }],
  ['bold', 'italic', 'underline', 'strike'],
  [{ color: [] }, { background: [] }],
  [{ list: 'ordered' }, { list: 'bullet' }],
  [{ indent: '-1' }, { indent: '+1' }],
  ['blockquote', 'code-block'],
  ['link'],
  ['clean']
];

onMounted(async () => {
  await nextTick();

  quill = new Quill(editorContainer.value, {
    theme: 'snow',
    placeholder: 'Viết nội dung bài viết tại đây...',
    modules: {
      toolbar: toolbarOptions
    }
  });

  // Load initial content using dangerouslyPasteHTML (proper Quill v2 way)
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

// Watch for external value changes (e.g. modal re-open with different article)
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
    <div ref="editorContainer"></div>
  </div>
</template>

<style scoped>
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

/* Dark mode via .dark class on html (Tailwind dark mode) */
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
