/* 
 * Copyright (C) 2018 ander
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
$(document).ready(function () {
    var id = 0;
    var canAdd = false;
    $("#pensum_grado").sortable();
    $("#pensum_grado").disableSelection();
    var academicid = $('#academicid').val();
    $(document).on('click', '.delete', function () {
        var id = $(this).attr('id');
        console.log(">>" + id);
        $.ajax({
            type: "POST",
            url: $('#dol_url').val() + "/educo/academic/events.php",
            data: {
                id: id,
                action: 'delete'
            },
            dataType: 'json',
            success: function (data) {
                $('#calendar').fullCalendar('removeEvents', id);
            }
        });
    });

    $('#calendar').fullCalendar({
        columnFormat: 'dddd',
        eventSources: [
            {
                color: 'coral',
                url: 'events.php',
                type: 'POST',
                resourceIds: ['a', 'b'],
                data: function () { // a function that returns an object
                    return {
                        academicid: academicid, groupid: $("#fk_group").val()
                    };
                },
                error: function () {
                    $('#script-warning').show();
                },
                success: function () {
                    var view = $('#calendar').fullCalendar('getView');
                }
            },
            {
                color: 'green',
                rendering: 'background',
                url: $('#dol_url').val() + "/educo/ajax/subjects_from_teacher.php",
                type: 'POST',
                data: function () { // a function that returns an object
                    return {
                        academicid: academicid, groupid: $("#fk_group").val(),
                        teacherid: $("#fk_user").val()
                    };
                },
                error: function () {
                    $('#script-warning').show();
                },
                success: function () {
                    var view = $('#calendar').fullCalendar('getView');
                }
            }
        ]


        ,
        slotLabelFormat: 'h(:mm)a',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        eventOverlap: true,
        eventRender: function (event, element) {

            var title = element.find('.fc-title');
            title.html(event.title);
            element.attr('title', event.tip);
            var cancel = $('<a  class="delete" style="position:absolute; top:2px; right:2px;"/>');
            cancel.text('X');
            cancel.attr('id', event.id);
            title.prepend(cancel);
        },
        select: function (start, end, jsEvent, view) {
            if (canAdd) {
                var optsubject = $('#subject option:selected');
                var optteacher = $('#fk_user option:selected');
                var optgroup = $('#fk_group option:selected');
                var newEvent = new Object();
                newEvent.title = optteacher.text() + "\n" + optsubject.text();
                // newEvent.id = id++;
                newEvent.start = moment(start).format();
                newEvent.end = moment(end).format();
                newEvent.allDay = false;
                newEvent.academicid = academicid;
                newEvent.teacherid = optteacher.val();
                // newEvent.groupid = optsubject.val();
                newEvent.teachsubid = optsubject.val();
                newEvent.subject_code = optsubject.attr("code");
                newEvent.groupid = optgroup.val();
                newEvent.grade_code = optgroup.attr("code");
                $.ajax({
                    type: "POST",
                    url: $('#dol_url').val() + "/educo/academic/events.php",
                    data: {
                        event: newEvent,
                        action: 'create'
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#calendar').fullCalendar('renderEvent', newEvent);
                        //  refreshPensum(academicid,$("fk_group option:").val());
                    }
                });

            }
        },
        header: {
            left: '',
            center: '',
            right: ''
        },
        defaultDate: moment(),
        timezone: "local",
        lang: 'es',
        defaultView: 'agendaWeek',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        slotDuration: '1:00',
        minTime: '7:00',
        maxTime: '18:00',
        droppable: true, // this allows things to be dropped onto the calendar
        dragRevertDuration: 0
    });
    $(".fc-agenda-slots").css("height", 200);



    $("#fk_group").change(function () {
        $('#title_grade').text($(this).find(":selected").text());
        $("#fk_user").trigger('change');
        $('#calendar').fullCalendar('refetchEvents');
        refreshPensum(academicid, $(this).val());
    });
    $('.fc-widget-content').hover(function () {
        var subjectid = $('#subject').val();
        if (subjectid > 0) {
            $(this).css('cursor', 'hand');
            canAdd = true;
            $(this).attr("title", "");
            // console.log("hover");
        } else {
            $(this).css('cursor', 'not-allowed');
            canAdd = false;
            $(this).attr("title", "SubjectEmpty");
        }
        $(this).tooltip();
    }, function () {
        $(this).css('cursor', 'pointer');
        // console.log("out");
    });

    var gradoid = $("#fk_group").val();
    $("#fk_user").change(function () {
          $('#title_teacher').text($(this).find(":selected").text());
        $('#calendar').fullCalendar('refetchEvents');
        $("#calendar").fullCalendar('refresh');
        var teacherid = $("#fk_user").val();
        $('#calendar').fullCalendar('refetchEvents');
        $.ajax({
            type: "POST",
            url: $('#dol_url').val() + "/educo/ajax/subjects_from_teacher_group.php",
            data: {
                academicid: academicid,
                teacherid: $(this).val()
            },
            dataType: 'json',
            success: function (data) {
                $('#subject').html('');
                $('#subject').append(new Option('', ''));
                //var pensum = $('input[name="subject_pensum[]"]').val();
                var pensum = $("input[name='subject_pensum[]']").map(function () {
                    return $(this).val();
                }).get();
                var codes = [];
                codes.push(pensum);
                //console.log(pensum);
                $.each(data, function (key, item) {
                    var option = $("<option value='" + item.rowid + "'/>");


                    if (!codes.includes(item.asignature_code)) {
                        // option.attr("disabled", "disabled");
                    }
                    option.attr("code", item.asignature_code);
                    option.text(item.subject_label);

                    $('#subject').append(option);
                });
            }
        });
    });
    function refreshPensum(academicid, groupid) {
        $.ajax({
            type: "POST",
            url: $('#dol_url').val() + "/educo/ajax/pensum_from_group.php",
            data: {
                academicid: academicid,
                groupid: groupid
            },
            dataType: 'json',
            success: function (data) {
                $('#subject').html('');
                $('#subject').append(new Option('', ''));
                var div = $('#pensum_grado');
                div.html('');
                var codes = [];

                $.each(data, function (key, item) {
                    var text = item.subject_label;
                    var hours = item.count + "/" + item.horas;
                    var code = item.asignature_code;
                    var item = $('<div class="boxstats"/>');

                    item.tooltip();
                    item.attr('title', text);
                    item.wrap('<a class="boxstatsindicator thumbstat nobold nounderline"/>');
                    var label = $('<span class="boxstatstext"/>');
                    var count = $('<span class="boxstatsindicator"/>');
                    var input = $('<input type="hidden" name="subject_pensum[]" value="' + code + '">');

                    item.append(label);
                    item.append('<br>');
                    item.append(count);
                    item.append(input);
                    label.append(text);
                    count.append(hours);
                    div.append(item);
                    codes.push(code);
                    //$('#list').listview('refresh');
                });
                // console.log(codes);
                $.data(div, "codes", codes);
            }
        });
    }
});
