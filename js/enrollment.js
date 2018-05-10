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
$(document).ready(function (e) {
   
    var dol_url = $('#student_url').val();
    var action;
    switch ($("#action").val()) {
        case 'add':
            action = 'create';
            break;
        case 'update':
            action = 'edit';
            break;
    }
    $('#academicid').change(function () {
        $("#form").attr("action", "?action=" +action );
        $('#form').submit();
    });
    $("#studentref").autocomplete({
        dataType: 'json',
        source: function (request, response) {
            $.ajax({
                url: dol_url,
                dataType: "json",
                data: {
                    find: request.term
                },
                success: function (data) {
                    var items = $.map(data, function (e) {
                        console.log(this);
                        return {
                            label: e.ref + " " + e.name,
                            value: e.ref,
                            id: e.id

                        };
                    });
                    response(items);
                }
            });
        },
        select: function (event, ui) {
            console.log(ui);
            $('#studentid').val(ui.item.id);
        },
        selectFirst: true,
        autoFill: true,
        // minChars: 2,
        mustMatch: true,
        matchContains: false,
        scrollHeight: 100,
        width: 300,
        cacheLength: 1,
        scroll: true
    });//.result(function(event, data, formatted) {
//        var n = $(this).attr("id").match(/\d+/);
//        var b = $("span[id='Desc"+n+"']");
//        b.html( !data ? "No match!" : "Selected: " + formatted);
//    });
});

