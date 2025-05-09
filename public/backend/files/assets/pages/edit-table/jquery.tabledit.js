/*!
 * Tabledit v1.2.3 (https://github.com/markcell/jQuery-Tabledit)
 * Copyright (c) 2015 Celso Marques
 * Licensed under MIT (https://github.com/markcell/jQuery-Tabledit/blob/master/LICENSE)
 */ if (typeof jQuery === "undefined") {
    throw new Error("Tabledit requires jQuery library.");
}
(function ($) {
    "use strict";
    $.fn.Tabledit = function (options) {
        if (!this.is("table")) {
            throw new Error("Tabledit only works when applied to a table.");
        }
        var $table = this;
        var defaults = {
            url: window.location.href,
            inputClass: "form-control input-sm",
            toolbarClass: "btn-toolbar",
            groupClass: "btn-group btn-group-sm",
            dangerClass: "danger",
            warningClass: "warning",
            mutedClass: "text-muted",
            eventType: "click",
            rowIdentifier: "id",
            hideIdentifier: false,
            autoFocus: true,
            editButton: true,
            deleteButton: true,
            saveButton: true,
            restoreButton: true,
            buttons: {
                edit: {
                    class: "btn btn-primary waves-effect waves-light",
                    html: '<span class="icofont icofont-ui-edit"></span>',
                    action: "edit",
                },
                delete: {
                    class: "btn btn-danger waves-effect waves-light",
                    html: '<span class="icofont icofont-ui-delete"></span>',
                    action: "delete",
                },
                save: { class: "btn btn-sm btn-success", html: "Save" },
                restore: {
                    class: "btn btn-sm btn-warning",
                    html: "Restore",
                    action: "restore",
                },
                confirm: { class: "btn btn-sm btn-danger", html: "Confirm" },
            },
            onDraw: function () {
                return;
            },
            onSuccess: function () {
                return;
            },
            onFail: function () {
                return;
            },
            onAlways: function () {
                return;
            },
            onAjax: function () {
                return;
            },
        };
        var settings = $.extend(true, defaults, options);
        var $lastEditedRow = "undefined";
        var $lastDeletedRow = "undefined";
        var $lastRestoredRow = "undefined";
        var Draw = {
            columns: {
                identifier: function () {
                    if (settings.hideIdentifier) {
                        $table
                            .find(
                                "th:nth-child(" +
                                    parseInt(settings.columns.identifier[0]) +
                                    1 +
                                    "), tbody td:nth-child(" +
                                    parseInt(settings.columns.identifier[0]) +
                                    1 +
                                    ")"
                            )
                            .hide();
                    }
                    var $td = $table.find(
                        "tbody td:nth-child(" +
                            (parseInt(settings.columns.identifier[0]) + 1) +
                            ")"
                    );
                    $td.each(function () {
                        var span =
                            '<span class="tabledit-span tabledit-identifier">' +
                            $(this).text() +
                            "</span>";
                        var input =
                            '<input class="tabledit-input tabledit-identifier" type="hidden" name="' +
                            settings.columns.identifier[1] +
                            '" value="' +
                            $(this).text() +
                            '" disabled>';
                        $(this).html(span + input);
                        $(this)
                            .parent("tr")
                            .attr(settings.rowIdentifier, $(this).text());
                    });
                },
                editable: function () {
                    for (var i = 0; i < settings.columns.editable.length; i++) {
                        var $td = $table.find(
                            "tbody td:nth-child(" +
                                (parseInt(settings.columns.editable[i][0]) +
                                    1) +
                                ")"
                        );
                        $td.each(function () {
                            var text = $(this).text();
                            if (!settings.editButton) {
                                $(this).css("cursor", "pointer");
                            }
                            var span =
                                '<span class="tabledit-span">' +
                                text +
                                "</span>";
                            if (
                                typeof settings.columns.editable[i][2] !==
                                "undefined"
                            ) {
                                var input =
                                    '<select class="tabledit-input ' +
                                    settings.inputClass +
                                    '" name="' +
                                    settings.columns.editable[i][1] +
                                    '" style="display: none;" disabled>';
                                $.each(
                                    jQuery.parseJSON(
                                        settings.columns.editable[i][2]
                                    ),
                                    function (index, value) {
                                        if (text === value) {
                                            input +=
                                                '<option value="' +
                                                index +
                                                '" selected>' +
                                                value +
                                                "</option>";
                                        } else {
                                            input +=
                                                '<option value="' +
                                                index +
                                                '">' +
                                                value +
                                                "</option>";
                                        }
                                    }
                                );
                                input += "</select>";
                            } else {
                                var input =
                                    '<input class="tabledit-input ' +
                                    settings.inputClass +
                                    '" type="text" name="' +
                                    settings.columns.editable[i][1] +
                                    '" value="' +
                                    $(this).text() +
                                    '" style="display: none;" disabled>';
                            }
                            $(this).html(span + input);
                            $(this).addClass("tabledit-view-mode");
                        });
                    }
                },
                toolbar: function () {
                    if (settings.editButton || settings.deleteButton) {
                        var editButton = "";
                        var deleteButton = "";
                        var saveButton = "";
                        var restoreButton = "";
                        var confirmButton = "";
                        if (
                            $table.find("th.tabledit-toolbar-column").length ===
                            0
                        ) {
                            $table
                                .find("tr:first")
                                .append(
                                    '<th class="tabledit-toolbar-column"></th>'
                                );
                        }
                        if (settings.editButton) {
                            editButton =
                                '<button type="button" class="tabledit-edit-button ' +
                                settings.buttons.edit.class +
                                '" style="float: none;margin: 5px;">' +
                                settings.buttons.edit.html +
                                "</button>";
                        }
                        if (settings.deleteButton) {
                            deleteButton =
                                '<button type="button" class="tabledit-delete-button ' +
                                settings.buttons.delete.class +
                                '" style="float: none;margin: 5px;">' +
                                settings.buttons.delete.html +
                                "</button>";
                            confirmButton =
                                '<button type="button" class="tabledit-confirm-button ' +
                                settings.buttons.confirm.class +
                                '" style="display: none; float: none;">' +
                                settings.buttons.confirm.html +
                                "</button>";
                        }
                        if (settings.editButton && settings.saveButton) {
                            saveButton =
                                '<button type="button" class="tabledit-save-button ' +
                                settings.buttons.save.class +
                                '" style="display: none; float: none;">' +
                                settings.buttons.save.html +
                                "</button>";
                        }
                        if (settings.deleteButton && settings.restoreButton) {
                            restoreButton =
                                '<button type="button" class="tabledit-restore-button ' +
                                settings.buttons.restore.class +
                                '" style="display: none; float: none;">' +
                                settings.buttons.restore.html +
                                "</button>";
                        }
                        var toolbar =
                            '<div class="tabledit-toolbar ' +
                            settings.toolbarClass +
                            '" style="text-align: left;">\n\
                                           <div class="' +
                            settings.groupClass +
                            '" style="float: none;">' +
                            editButton +
                            deleteButton +
                            "</div>\n\
                                           " +
                            saveButton +
                            "\n\
                                           " +
                            confirmButton +
                            "\n\
                                           " +
                            restoreButton +
                            "\n\
                                       </div></div>";
                        $table
                            .find("tr:gt(0)")
                            .append(
                                '<td style="white-space: nowrap; width: 1%;">' +
                                    toolbar +
                                    "</td>"
                            );
                    }
                },
            },
        };
        var Mode = {
            view: function (td) {
                var $tr = $(td).parent("tr");
                $(td)
                    .parent("tr")
                    .find(".tabledit-input.tabledit-identifier")
                    .prop("disabled", true);
                $(td)
                    .find(".tabledit-input")
                    .blur()
                    .hide()
                    .prop("disabled", true);
                $(td).find(".tabledit-span").show();
                $(td)
                    .addClass("tabledit-view-mode")
                    .removeClass("tabledit-edit-mode");
                if (settings.editButton) {
                    $tr.find("button.tabledit-save-button").hide();
                    $tr.find("button.tabledit-edit-button")
                        .removeClass("active")
                        .blur();
                }
            },
            edit: function (td) {
                Delete.reset(td);
                var $tr = $(td).parent("tr");
                $tr.find(".tabledit-input.tabledit-identifier").prop(
                    "disabled",
                    false
                );
                $(td).find(".tabledit-span").hide();
                var $input = $(td).find(".tabledit-input");
                $input.prop("disabled", false).show();
                if (settings.autoFocus) {
                    $input.focus();
                }
                $(td)
                    .addClass("tabledit-edit-mode")
                    .removeClass("tabledit-view-mode");
                if (settings.editButton) {
                    $tr.find("button.tabledit-edit-button").addClass("active");
                    $tr.find("button.tabledit-save-button").show();
                }
            },
        };
        var Edit = {
            reset: function (td) {
                $(td).each(function () {
                    var $input = $(this).find(".tabledit-input");
                    var text = $(this).find(".tabledit-span").text();
                    if ($input.is("select")) {
                        $input
                            .find("option")
                            .filter(function () {
                                return $.trim($(this).text()) === text;
                            })
                            .attr("selected", true);
                    } else {
                        $input.val(text);
                    }
                    Mode.view(this);
                });
            },
            submit: function (td) {
                var ajaxResult = ajax(settings.buttons.edit.action);
                if (ajaxResult === false) {
                    return;
                }
                $(td).each(function () {
                    var $input = $(this).find(".tabledit-input");
                    if ($input.is("select")) {
                        $(this)
                            .find(".tabledit-span")
                            .text($input.find("option:selected").text());
                    } else {
                        $(this).find(".tabledit-span").text($input.val());
                    }
                    Mode.view(this);
                });
                $lastEditedRow = $(td).parent("tr");
            },
        };
        var Delete = {
            reset: function (td) {
                $table.find(".tabledit-confirm-button").hide();
                $table
                    .find(".tabledit-delete-button")
                    .removeClass("active")
                    .blur();
            },
            submit: function (td) {
                Delete.reset(td);
                $(td)
                    .parent("tr")
                    .find("input.tabledit-identifier")
                    .attr("disabled", false);
                var ajaxResult = ajax(settings.buttons.delete.action);
                $(td)
                    .parents("tr")
                    .find("input.tabledit-identifier")
                    .attr("disabled", true);
                if (ajaxResult === false) {
                    return;
                }
                $(td).parent("tr").addClass("tabledit-deleted-row");
                $(td)
                    .parent("tr")
                    .addClass(settings.mutedClass)
                    .find(
                        ".tabledit-toolbar button:not(.tabledit-restore-button)"
                    )
                    .attr("disabled", true);
                $(td).find(".tabledit-restore-button").show();
                $lastDeletedRow = $(td).parent("tr");
                var abcd = $(td)
                    .parent()
                    .parent()
                    .children()
                    .index($(td).parent("tr"));
                var a = abcd + 1;
                document.getElementById("example-2").deleteRow(a);
            },
            confirm: function (td) {
                $table.find("td.tabledit-edit-mode").each(function () {
                    Edit.reset(this);
                });
                $(td).find(".tabledit-delete-button").addClass("active");
                $(td).find(".tabledit-confirm-button").show();
            },
            restore: function (td) {
                $(td)
                    .parent("tr")
                    .find("input.tabledit-identifier")
                    .attr("disabled", false);
                var ajaxResult = ajax(settings.buttons.restore.action);
                $(td)
                    .parents("tr")
                    .find("input.tabledit-identifier")
                    .attr("disabled", true);
                if (ajaxResult === false) {
                    return;
                }
                $(td).parent("tr").removeClass("tabledit-deleted-row");
                $(td)
                    .parent("tr")
                    .removeClass(settings.mutedClass)
                    .find(".tabledit-toolbar button")
                    .attr("disabled", false);
                $(td).find(".tabledit-restore-button").hide();
                $lastRestoredRow = $(td).parent("tr");
            },
        };
        function ajax(action) {
            var serialize =
                $table.find(".tabledit-input").serialize() +
                "&action=" +
                action;
            var result = settings.onAjax(action, serialize);
            if (result === false) {
                return false;
            }
            var jqXHR = $.post(
                settings.url,
                serialize,
                function (data, textStatus, jqXHR) {
                    if (action === settings.buttons.edit.action) {
                        $lastEditedRow
                            .removeClass(settings.dangerClass)
                            .addClass(settings.warningClass);
                        setTimeout(function () {
                            $table
                                .find("tr." + settings.warningClass)
                                .removeClass(settings.warningClass);
                        }, 1400);
                    }
                    settings.onSuccess(data, textStatus, jqXHR);
                },
                "json"
            );
            jqXHR.fail(function (jqXHR, textStatus, errorThrown) {
                if (action === settings.buttons.delete.action) {
                    $lastDeletedRow
                        .removeClass(settings.mutedClass)
                        .addClass(settings.dangerClass);
                    $lastDeletedRow
                        .find(".tabledit-toolbar button")
                        .attr("disabled", false);
                    $lastDeletedRow
                        .find(".tabledit-toolbar .tabledit-restore-button")
                        .hide();
                } else if (action === settings.buttons.edit.action) {
                    $lastEditedRow.addClass(settings.dangerClass);
                }
                settings.onFail(jqXHR, textStatus, errorThrown);
            });
            jqXHR.always(function () {
                settings.onAlways();
            });
            return jqXHR;
        }
        Draw.columns.identifier();
        Draw.columns.editable();
        Draw.columns.toolbar();
        settings.onDraw();
        if (settings.deleteButton) {
            $table.on(
                "click",
                "button.tabledit-delete-button",
                function (event) {
                    if (event.handled !== true) {
                        event.preventDefault();
                        var activated = $(this).hasClass("active");
                        var $td = $(this).parents("td");
                        Delete.reset($td);
                        if (!activated) {
                            Delete.confirm($td);
                        }
                        event.handled = true;
                    }
                }
            );
            $table.on(
                "click",
                "button.tabledit-confirm-button",
                function (event) {
                    if (event.handled !== true) {
                        event.preventDefault();
                        var $td = $(this).parents("td");
                        Delete.submit($td);
                        event.handled = true;
                    }
                }
            );
        }
        if (settings.restoreButton) {
            $table.on(
                "click",
                "button.tabledit-restore-button",
                function (event) {
                    if (event.handled !== true) {
                        event.preventDefault();
                        Delete.restore($(this).parents("td"));
                        event.handled = true;
                    }
                }
            );
        }
        if (settings.editButton) {
            $table.on("click", "button.tabledit-edit-button", function (event) {
                if (event.handled !== true) {
                    event.preventDefault();
                    var $button = $(this);
                    var activated = $button.hasClass("active");
                    Edit.reset($table.find("td.tabledit-edit-mode"));
                    if (!activated) {
                        $(
                            $button
                                .parents("tr")
                                .find("td.tabledit-view-mode")
                                .get()
                                .reverse()
                        ).each(function () {
                            Mode.edit(this);
                        });
                    }
                    event.handled = true;
                }
            });
            $table.on("click", "button.tabledit-save-button", function (event) {
                if (event.handled !== true) {
                    event.preventDefault();
                    Edit.submit(
                        $(this).parents("tr").find("td.tabledit-edit-mode")
                    );
                    event.handled = true;
                }
            });
        } else {
            $table.on(
                settings.eventType,
                "tr:not(.tabledit-deleted-row) td.tabledit-view-mode",
                function (event) {
                    if (event.handled !== true) {
                        event.preventDefault();
                        Edit.reset($table.find("td.tabledit-edit-mode"));
                        Mode.edit(this);
                        event.handled = true;
                    }
                }
            );
            $table.on("change", "select.tabledit-input:visible", function () {
                if (event.handled !== true) {
                    Edit.submit($(this).parent("td"));
                    event.handled = true;
                }
            });
            $(document).on("click", function (event) {
                var $editMode = $table.find(".tabledit-edit-mode");
                if (
                    !$editMode.is(event.target) &&
                    $editMode.has(event.target).length === 0
                ) {
                    Edit.reset(
                        $table.find(".tabledit-input:visible").parent("td")
                    );
                }
            });
        }
        $(document).on("keyup", function (event) {
            var $input = $table.find(".tabledit-input:visible");
            var $button = $table.find(".tabledit-confirm-button");
            if ($input.length > 0) {
                var $td = $input.parents("td");
            } else if ($button.length > 0) {
                var $td = $button.parents("td");
            } else {
                return;
            }
            switch (event.keyCode) {
                case 9:
                    if (!settings.editButton) {
                        Edit.submit($td);
                        Mode.edit($td.closest("td").next());
                    }
                    break;
                case 13:
                    Edit.submit($td);
                    break;
                case 27:
                    Edit.reset($td);
                    Delete.reset($td);
                    break;
            }
        });
        return this;
    };
})(jQuery);
