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
     var academicid=$('#academicid').val();
    $("#fk_group").change(function () {
        console.log($(this).val());
        var teacherid=$("#fk_user").val();
        $.ajax({
            type: "POST",
            url: $('#dol_url').val() + "/educo/ajax/teacher_from_group.php",
            data: {
                academicid:academicid ,
                groupid:$(this).val(),
                teacherid: teacherid
            },
            dataType: 'json',
            success: function (data) {
             
                $('#subject').html('');
                $('#subject').append(new Option('', ''));
                $.each(data, function (key, item) {
                    console.log(item);
                    $('#subject').append(new Option(item.subject_label, item.rowid));
                });
            }
        });
    });
    var gradoid=$("#fk_group").val();
//    $("#fk_user").change(function () {
//        console.log($(this).val());
//        $.ajax({
//            type: "POST",
//            url: $('#dol_url').val() + "/educo/ajax/teacher_from_group.php",
//            data: {
//                busqueda: $(this).val(),
//                tipo: 'grupo'
//            },
//            success: function (data) {
//                var opts = $.parseJSON(data);
//                $('#grupo').html('');
//                $('#grupo').append(new Option('', ''));
//                $.each(opts, function (key, item) {
//                    $('#grupo').append(new Option(item.ref + ': ' + item.descripcion, item.ref));
//                });
//            }
//        });
//    });
    
});
