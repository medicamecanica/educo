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
    var groupid = $('#groupid').val();
    var teacherid = $('#fk_user').val();
    var teachersubjectid = $('#teachersubjectid').val();
    if (teachersubjectid > 0)
        canAdd = true;
    $(document).on('click', '.delete', function () {

    });
    $('#save').click(function (e) {
        e.preventDefault();
        var events = $('#calendar').fullCalendar('clientEvents');
        var newevents = [];
        $.each(events, function (key, item) {
            if (item.id === undefined) {
                newevents.push(item);
            }
            if (item.edit) {
                newevents.push(item);
            }
        });
        var param= $('<input name="events" type="hidden">');
        var save= $('<input name="save" value="1" type="hidden">');
        param.attr('value', JSON.stringify(newevents));
        $('#form').append(param).append(save).submit();

    });
    $('#calendar').fullCalendar({
        columnFormat: 'dddd',
        defaultDate: '2017-01-01',
        slotLabelFormat: 'h(:mm)a',
        allDaySlot: false,
        height: 'auto',
        selectable: true,
        eventOverlap: true,
        header: {
            left: '',
            center: '',
            right: ''
        },
        timezone: "local",
        lang: 'es',
        defaultView: 'agendaWeek',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        slotDuration: '1:00',
        minTime: '7:00',
        maxTime: '18:00',
        droppable: true, // this allows things to be dropped onto the calendar
        dragRevertDuration: 0,
        eventSources: [
            {
                color: 'coral',
                url: 'events.php',
                type: 'POST',
                resourceIds: ['a', 'b'],
                data: function () { // a function that returns an object
                    return {
                        academicid: academicid, groupid: $("#groupid").val()
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
                        academicid: academicid, groupid: $("#groupid").val(),
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
        eventDrop: function (event, delta, revertFunc) {
            event.edit = true;
        },
        eventResize: function (event, delta, revertFunc) {
            event.edit = true;
        },
        eventRender: function (event, element) {

            var title = element.find('.fc-title');
            title.html(event.title);
            element.attr('title', event.tip);
            if (event.id > 0) {
                var cancel = $('<a  class="delete" style="position:absolute; top:2px; right:2px;"/>');
                cancel.text('X');
                cancel.attr('id', event.id);
                cancel.attr('href', '?academicid=' + academicid +
                        '&groupid=' + groupid +
                        '&fk_user=' + teacherid +
                        '&teachersubjectid=' + teachersubjectid +
                        '&action=delete&id=' + event.id);
                title.prepend(cancel);
            }
        },
        select: function (start, end, jsEvent, view) {
            var cell = $(jsEvent.target);

            if (canAdd) {
                var optsubject = $('#teachersubjectid option:selected');
                var optteacher = $('#fk_user option:selected');
                var optgroup = $('#groupid option:selected');
                var newEvent = new Object();
                newEvent.title =  optsubject.text();
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
                $('#calendar').fullCalendar('renderEvent', newEvent);
//             

            }
        }
    });
    $(".fc-agenda-slots").css("height", 200);



    $("#groupid").change(function () {
        $('#title_grade').text($(this).find(":selected").text());
        $("#fk_user").trigger('change');
       // $('#calendar').fullCalendar('refetchEvents');
    });
    $('.fc-widget-content').hover(function () {
        var subjectid = $('#teachersubjectid').val();
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

    var gradoid = $("#groupid").val();
    $("#fk_user").change(function () {

    });

});
