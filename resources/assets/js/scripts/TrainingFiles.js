class TrainingFiles {
    constructor() {
        this.selectedFilesIds = [];

        this.init()
    }

    init() {
        let modal = $('.files-modal').not('.read-only');

        if (modal.length) {
            modal.find('.file-item .file-title').on('click', event => {
                this.handleFileSelection($(event.currentTarget).closest('.file-item'))
            });

            modal.find('.delete-files').on('click', event => {
                this.confirmFilesSelections($(event.currentTarget))
            });

            modal.find('button[data-dismiss=modal]').on('click', event => {
                this.uncheckAllFiles($(event.currentTarget).closest('.files-modal'))
            });
        }
    }

    handleFileSelection(itemBlock) {
        let fileId = itemBlock.data('id');

        if (fileId === undefined) {
            return;
        }

        itemBlock.toggleClass('selected')

        if (itemBlock.hasClass('selected')) {
            if (!_.includes(this.selectedFilesIds, fileId)) {
                this.selectedFilesIds.push(fileId)
            }
        } else {
            this.selectedFilesIds = this.selectedFilesIds.filter(id => id !== fileId)
        }

        this.handleDeleteFilesBtn(itemBlock)
    }

    handleDeleteFilesBtn(itemBlock) {
        let confirmBtn = itemBlock.closest('.files-modal').find('.delete-files');

        if (!confirmBtn.length) {
            return;
        }

        confirmBtn.prop('disabled', !this.selectedFilesIds.length)
    }

    confirmFilesSelections(that) {
        that.closest('.files-modal').find('.deleted-files-checkbox').map((index, checkbox) => {
            $(checkbox).prop('checked', this.selectedFilesIds.includes(parseInt($(checkbox).val())))
        })

        that.closest('.files-modal').modal('hide')
    }

    uncheckAllFiles(modal) {
        this.selectedFilesIds = [];
        modal.find('.deleted-files-checkbox').prop('checked', false)
        modal.find('.files-wrap .file-item').removeClass('selected')
        modal.find('.delete-files').prop('disabled', true)
        modal.modal('hide')
    }
}

module.exports = TrainingFiles;
