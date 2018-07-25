<template>
    <div id="editor" ref="quill"></div>
</template>

<script>
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.core.css'
import Quill from 'quill'

export default {
    props: {
        value: String,
        config: {
            default() {
                return {
                    placeholder: 'Описание товара',
                    modules: {
                        toolbar: [
                          [{ header: [2, 3, false] }],
                          ['bold', 'italic', 'underline'],
                          [{ 'list': 'ordered'}, { 'list': 'bullet' }], 
                          ['clean']
                        ]
                      },
                    theme: 'snow',
                }
            },
        },
    },
    data(){
        return {
            quill: {},
            content: ''
        }
    },
    mounted(){

        this.quill = new Quill(this.$refs.quill, this.config)
        // this.quill.clipboard.dangerouslyPasteHTML(0, this.value)
        // this.quill.pasteHTML(this.value)
        let self = this
        this.quill.on('text-change', function() {
            if(self.quill.getLength() <= 1) self.content = ''
            else self.content = self.quill.container.firstChild.innerHTML
            self.$emit('input', self.content)
        });
    },
    watch: {
        value(newVal, oldVal) {
            if (this.quill) {
                if(newVal !== this.content) {
                    this.quill.pasteHTML(newVal)
                }
            }
        }
    },

}
</script>
