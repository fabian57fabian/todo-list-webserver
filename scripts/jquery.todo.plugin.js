(function () {
    $.fn.todo = function (options) {
        console.log("call plugin");
        var defaults = {
            serverURL: "example.com/server_page_url"
        }
        options = $.extend(defaults, options);
        console.log("options serverURL: " + options["serverURL"]);

        return this.each(function (i, obj) {
            var $this = $(this);
            $this.wrap("<div class='plugin_wrapper'/>");

            $this.addClass("to-do-list-container");
            $('<h2>My to do list</h2>' +
                '<textarea class="todo_textarea"></textarea>' +
                '<input type="submit" value="add to do" class="todo_submit"/>').insertBefore($this);
            var $submitButton = $('.todo_submit', $this.parent());
            $submitButton.on('click', function () {
                console.log("click");
                sendToDo($this);
            })
            loadToDo($this);
        });

        function sendToDo($el) {
            console.log("send todo");
            var $this = $el;
            request_type = "insert";
            var $todoText = $this.parent().find(".todo_textarea");
            var todoText = $todoText.val();
            $todoText.val("");

            if (todoText.length > 2) {
                var request = $.ajax({
                    url: options.serverURL,
                    type: "post",
                    data: {"text": todoText, "action": request_type},
                    dataTyp: "json"
                });
                request.done(function (data) {
                    console.log("REQUEST.DONE: " + data);
                    handleInsert(data, $this);
                })
                request.fail(function (jqXHR, textStatus) {
                    console.log("REQUEST.FAIL: " + textStatus);
                })
            }
        }

        function loadToDo($el) {
            console.log("load todo");
        }

        function handleInsert(data, $el) {
            console.log("handleInsert");
            var $this = $el;
            var todos = data['todos'];
            var html = "";

            if (!($(".todo-list", $this))) {
                var toDoList = $('<ul class="todo-list"></ul>');
                $this.append(toDoList);
            }
            html += "<li data-id='todo_" +
                todos[0]['id'] + "'>" +
                "<span class='todo_text'>" + todos[0]['text'] + "</span>" +
                "<span class='deleter'>x</span></li>\n";
            $('.todo-list', $this.prepend(html));
        }
    }
})(jQuery)