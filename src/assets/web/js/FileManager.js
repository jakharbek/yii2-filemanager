var FileManager = function (id, input_id, delimtr, close_btn, isEditor,modal) {
    var self = this;
    this.id = id;
    this.input_id = input_id;
    this.input = $(this.input_id);
    this.items = $(this.id);
    this.delimtr = ",";
    this.editor = null;
    this.isEditor = isEditor;
    this.modal = modal;

    if (delimtr !== null) {
        this.delimtr = delimtr;
    }
    try {

        this.ids = self.input.val().split(self.delimtr);

    } catch (e) {
        this.ids = [];
    }
    this.addItem = function (file_id, file_link, file_ext) {

        if (self.isEditor) {
            if (file_ext == "jpg" || file_ext == "jpeg" || file_ext == "svg" || file_ext == "bmp" || file_ext == "png" || file_ext == "gif") {
                self.editor.insertHtml('<img src="' + file_link + '" />');
            }
            if (file_ext == "mp4" || file_ext == "flv") {
                self.editor.insertHtml('<video width="320" height="240" controls src="' + file_link + '"></video>');
            }
            if (file_ext == "mp3") {
                self.editor.insertHtml('<audio controls src="' + file_link + '"></audio>');
            }
            return true;
        }
        var data = '<li data-key="' + file_id + '" draggable="true" role="option" aria-grabbed="false">' +
            '<div class="file-input-item" style="background-image: url(' + file_link + ')">' +
            '<div class="' + close_btn + ' btn btn-danger" data-file-id="' + file_id + '"><i class="fa fa-minus"></i></div>' +
            '</div>' +
            '</li>';
        console.log(this.id);
        self.items.append(data);
        console.log(close_btn);
        console.log($(self.id).find("[data-key=" + file_id + "]").find("." + close_btn));
        $(self.id).find("[data-key=" + file_id + "]").find("." + close_btn).off("click");
        $(self.id).find("[data-key=" + file_id + "]").find("." + close_btn).click(function (e) {
            var file_id = ($(this).data("file-id"));
            self.removeItem(file_id, this);
        });
        self.ids.push(file_id);
        self.input.val(self.ids.join(self.delimtr));
        console.log($(self.id));
    }
    this.removeItem = function (file_id, _this) {
        $(_this).closest("li").remove();
        self.ids = self.input.val().split(self.delimtr);
        var index = self.ids.indexOf(file_id.toString());
        console.log(file_id);
        console.log(index);
        console.log(self.ids);
        if (index > -1) {
            self.ids.splice(index, 1);
        }
        self.input.val(self.ids.join(self.delimtr));
    }

    this.initEditor = function (editor) {
        self.editor = editor;
        $(self.modal).modal('show');
    }
}