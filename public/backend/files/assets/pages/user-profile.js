"use strict";
$(document).ready(function () {
    $(window).on("resize", function () {
        dashboardEcharts();
    });
    $(window).on("load", function () {
        dashboardEcharts();
    });
    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        dashboardEcharts();
    });
    function dashboardEcharts() {
        var myChart = echarts.init(document.getElementById("main"));
        var option = {
            tooltip: {
                trigger: "item",
                formatter: function (params) {
                    var date = new Date(params.value[0]);
                    var data =
                        date.getFullYear() +
                        "-" +
                        (date.getMonth() + 1) +
                        "-" +
                        date.getDate() +
                        " " +
                        date.getHours() +
                        ":" +
                        date.getMinutes();
                    return (
                        data +
                        "<br/>" +
                        params.value[1] +
                        ", " +
                        params.value[2]
                    );
                },
                responsive: true,
            },
            dataZoom: { show: true, start: 70 },
            legend: { data: ["Profit"] },
            grid: { y2: 80 },
            xAxis: [{ type: "time", splitNumber: 10 }],
            yAxis: [{ type: "value" }],
            series: [
                {
                    name: "Profit",
                    type: "line",
                    showAllSymbol: true,
                    symbolSize: function (value) {
                        return Math.round(value[2] / 10) + 2;
                    },
                    data: (function () {
                        var d = [];
                        var len = 0;
                        var now = new Date();
                        var value;
                        while (len++ < 200) {
                            d.push([
                                new Date(2014, 9, 1, 0, len * 10000),
                                (Math.random() * 30).toFixed(2) - 0,
                                (Math.random() * 100).toFixed(2) - 0,
                            ]);
                        }
                        return d;
                    })(),
                },
            ],
        };
        myChart.setOption(option);
    }
    $("#simpletable").DataTable({
        paging: true,
        ordering: true,
        bLengthChange: true,
        info: true,
        searching: true,
    });
    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    $("#edit-cancel").on("click", function () {
        var c = $("#edit-btn").find("i");
        c.removeClass("icofont-close");
        c.addClass("icofont-edit");
        $(".view-info").show();
        $(".edit-info").hide();
    });
    $(".edit-info").hide();
    $("#edit-btn").on("click", function () {
        var b = $(this).find("i");
        var edit_class = b.attr("class");
        if (edit_class == "icofont icofont-edit") {
            b.removeClass("icofont-edit");
            b.addClass("icofont-close");
            $(".view-info").hide();
            $(".edit-info").show();
        } else {
            b.removeClass("icofont-close");
            b.addClass("icofont-edit");
            $(".view-info").show();
            $(".edit-info").hide();
        }
    });
    CKEDITOR.replace("description", {
        toolbar: [
            { name: "clipboard", items: ["Undo", "Redo"] },
            { name: "styles", items: ["Styles", "Format"] },
            {
                name: "basicstyles",
                items: ["Bold", "Italic", "Strike", "-", "RemoveFormat"],
            },
            {
                name: "paragraph",
                items: [
                    "NumberedList",
                    "BulletedList",
                    "-",
                    "Outdent",
                    "Indent",
                    "-",
                    "Blockquote",
                ],
            },
            { name: "links", items: ["Link", "Unlink"] },
            { name: "insert", items: ["Image", "EmbedSemantic", "Table"] },
            { name: "tools", items: ["Maximize"] },
            { name: "editing", items: ["Scayt"] },
        ],
        customConfig: "",
        extraPlugins: "autoembed,embedsemantic,image2,uploadimage,uploadfile",
        imageUploadUrl: "/uploader/upload.php?type=Images",
        uploadUrl: "/uploader/upload.php",
        removePlugins: "image",
        height: 400,
        bodyClass: "article-editor",
        format_tags: "p;h1;h2;h3;pre",
        removeDialogTabs: "image:advanced;link:advanced",
        stylesSet: [
            {
                name: "Marker",
                element: "span",
                attributes: { class: "marker" },
            },
            { name: "Cited Work", element: "cite" },
            { name: "Inline Quotation", element: "q" },
            {
                name: "Special Container",
                element: "div",
                styles: {
                    padding: "5px 10px",
                    background: "#eee",
                    border: "1px solid #ccc",
                },
            },
            {
                name: "Compact table",
                element: "table",
                attributes: {
                    cellpadding: "5",
                    cellspacing: "0",
                    border: "1",
                    bordercolor: "#ccc",
                },
                styles: { "border-collapse": "collapse" },
            },
            {
                name: "Borderless Table",
                element: "table",
                styles: {
                    "border-style": "hidden",
                    "background-color": "#E6E6FA",
                },
            },
            {
                name: "Square Bulleted List",
                element: "ul",
                styles: { "list-style-type": "square" },
            },
            {
                name: "240p",
                type: "widget",
                widget: "embedSemantic",
                attributes: { class: "embed-240p" },
            },
            {
                name: "360p",
                type: "widget",
                widget: "embedSemantic",
                attributes: { class: "embed-360p" },
            },
            {
                name: "480p",
                type: "widget",
                widget: "embedSemantic",
                attributes: { class: "embed-480p" },
            },
            {
                name: "720p",
                type: "widget",
                widget: "embedSemantic",
                attributes: { class: "embed-720p" },
            },
            {
                name: "1080p",
                type: "widget",
                widget: "embedSemantic",
                attributes: { class: "embed-1080p" },
            },
        ],
    });
    $("#edit-cancel-btn").on("click", function () {
        var c = $("#edit-info-btn").find("i");
        c.removeClass("icofont-close");
        c.addClass("icofont-edit");
        $(".view-desc").show();
        $(".edit-desc").hide();
    });
    $(".edit-desc").hide();
    $("#edit-info-btn").on("click", function () {
        var b = $(this).find("i");
        var edit_class = b.attr("class");
        if (edit_class == "icofont icofont-edit") {
            b.removeClass("icofont-edit");
            b.addClass("icofont-close");
            $(".view-desc").hide();
            $(".edit-desc").show();
        } else {
            b.removeClass("icofont-close");
            b.addClass("icofont-edit");
            $(".view-desc").show();
            $(".edit-desc").hide();
        }
    });
    $("#datetimepicker1").datetimepicker({
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker2").datetimepicker({
        locale: "ru",
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker3").datetimepicker({
        format: "LT",
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker4").datetimepicker({
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker5").datetimepicker({
        defaultDate: "11/1/2013",
        disabledDates: [
            moment("12/25/2013"),
            new Date(2013, 11 - 1, 21),
            "11/22/2013 00:53",
        ],
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker6").datetimepicker({
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker7").datetimepicker({
        useCurrent: false,
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker6").on("dp.change", function (e) {
        $("#datetimepicker7").data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $("#datetimepicker6").data("DateTimePicker").maxDate(e.date);
    });
    $("#datetimepicker8").datetimepicker({
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
        },
    });
    $("#datetimepicker9").datetimepicker({
        viewMode: "years",
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker10").datetimepicker({
        viewMode: "years",
        format: "MM/YYYY",
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $("#datetimepicker11").datetimepicker({
        daysOfWeekDisabled: [0, 6],
        icons: {
            time: "icofont icofont-clock-time",
            date: "icofont icofont-ui-calendar",
            up: "icofont icofont-rounded-up",
            down: "icofont icofont-rounded-down",
            next: "icofont icofont-rounded-right",
            previous: "icofont icofont-rounded-left",
        },
    });
    $('input[name="daterange"]').daterangepicker();
    $(function () {
        $('input[name="birthdate"]').daterangepicker(
            { singleDatePicker: true, showDropdowns: true },
            function (start, end, label) {
                var years = moment().diff(start, "years");
                alert("You are " + years + " years old.");
            }
        );
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: "Clear" },
        });
        $('input[name="datefilter"]').on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                    picker.startDate.format("MM/DD/YYYY") +
                        " - " +
                        picker.endDate.format("MM/DD/YYYY")
                );
            }
        );
        $('input[name="datefilter"]').on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
            }
        );
        var start = moment().subtract(29, "days");
        var end = moment();
        function cb(start, end) {
            $("#reportrange span").html(
                start.format("MMMM D, YYYY") +
                    " - " +
                    end.format("MMMM D, YYYY")
            );
        }
        $("#reportrange").daterangepicker(
            {
                startDate: start,
                endDate: end,
                drops: "up",
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            },
            cb
        );
        cb(start, end);
        $(".input-daterange input").each(function () {
            $(this).datepicker();
        });
        $("#sandbox-container .input-daterange").datepicker({
            todayHighlight: true,
        });
        $(".input-group-date-custom").datepicker({
            todayBtn: true,
            clearBtn: true,
            keyboardNavigation: false,
            forceParse: false,
            todayHighlight: true,
            defaultViewDate: { year: "2017", month: "01", day: "01" },
        });
        $(".multiple-select").datepicker({
            todayBtn: true,
            clearBtn: true,
            multidate: true,
            keyboardNavigation: false,
            forceParse: false,
            todayHighlight: true,
            defaultViewDate: { year: "2017", month: "01", day: "01" },
        });
        $("#config-demo").daterangepicker(
            {
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                showCustomRangeLabel: false,
                alwaysShowCalendars: true,
                startDate: "11/30/2016",
                endDate: "12/06/2016",
                drops: "up",
            },
            function (start, end, label) {
                console.log(
                    "New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')"
                );
            }
        );
    });
    $("#dropper-default").dateDropper({
        dropWidth: 200,
        dropPrimaryColor: "#1abc9c",
        dropBorder: "1px solid #1abc9c",
    }),
        $(".demo").each(function () {
            $(this).minicolors({
                control: $(this).attr("data-control") || "hue",
                defaultValue: $(this).attr("data-defaultValue") || "",
                format: $(this).attr("data-format") || "hex",
                keywords: $(this).attr("data-keywords") || "",
                inline: $(this).attr("data-inline") === "true",
                letterCase: $(this).attr("data-letterCase") || "lowercase",
                opacity: $(this).attr("data-opacity"),
                position: $(this).attr("data-position") || "bottom left",
                swatches: $(this).attr("data-swatches")
                    ? $(this).attr("data-swatches").split("|")
                    : [],
                change: function (value, opacity) {
                    if (!value) return;
                    if (opacity) value += ", " + opacity;
                    if (typeof console === "object") {
                        console.log(value);
                    }
                },
                theme: "bootstrap",
            });
        });
});
