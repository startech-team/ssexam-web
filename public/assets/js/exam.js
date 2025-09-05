// // 問題選択
// function selectQuestion(question_id, question_type_nm, question_title) {

//     var ques_id_arr = document.getElementById("ques_id_arr").value;
//     if (ques_id_arr != null && ques_id_arr != '') {
//         let ques_arr = ques_id_arr.split(',');
//         if (ques_arr.includes(question_id)) {
//             alert("重複している問題があります。");
//             return;
//         } else {
//             ques_id_arr = ques_id_arr + "," + question_id;
//         }
//     } else {
//         ques_id_arr = question_id + "";
//     }
//     document.getElementById("ques_id_arr").value = ques_id_arr;

//     var table = document.getElementById("question_tbl").getElementsByTagName('tbody')[0];
//     var row = table.insertRow();
//     row.setAttribute("id", "ques_" + question_id);
//     var cell1 = row.insertCell();
//     var cell2 = row.insertCell();
//     var cell3 = row.insertCell();
//     var cell4 = row.insertCell();
//     var cell5 = row.insertCell();
//     var q_no = document.createTextNode(table.rows.length - 1);
//     var q_type = document.createTextNode(question_type_nm);
//     var q_title = document.createTextNode(question_title);
//     var question_body = $("#body" + question_id).text();
//     var q_body = document.createTextNode(question_body);
//     var a = document.createElement("a");
//     a.text = "取消";
//     a.href = "#";
//     a.addEventListener("click", function () {
//         removeQues(question_id)
//     });
//     cell1.appendChild(q_no);
//     cell2.appendChild(q_type);
//     cell3.appendChild(q_title);
//     cell4.appendChild(q_body);
//     cell5.appendChild(a);
//     $("#closeQuesModalBtn").click();
// }
// function selectQuestionUpdate(question_id, question_type_nm, question_title) {

//     var ques_id_arr = document.getElementById("ques_id_arr").value;
//     if (ques_id_arr != null && ques_id_arr != '') {
//         let ques_arr = ques_id_arr.split(',');
//         if (ques_arr.includes(question_id)) {
//             alert("重複している問題があります。");
//             return;
//         } else {
//             ques_id_arr = ques_id_arr + "," + question_id;
//         }
//     } else {
//         ques_id_arr = question_id + "";
//     }
//     document.getElementById("ques_id_arr").value = ques_id_arr;

//     var table = document.getElementById("question_tbl").getElementsByTagName('tbody')[0];
//     var row = table.insertRow(-1);
//     row.setAttribute("id", "ques_" + question_id);
//     var cell1 = row.insertCell();
//     var cell2 = row.insertCell();
//     var cell3 = row.insertCell();
//     var cell4 = row.insertCell();
//     var cell5 = row.insertCell();
//     var q_no = document.createTextNode(table.rows.length);
//     var q_type = document.createTextNode(question_type_nm);
//     var q_title = document.createTextNode(question_title);
//     var question_body = $("#body" + question_id).text();
//     var q_body = document.createTextNode(question_body);
//     var a = document.createElement("a");
//     a.text = "取消";
//     a.href = "#";
//     a.addEventListener("click", function () {
//         removeQues(question_id)
//     });
//     cell1.appendChild(q_no);
//     cell2.appendChild(q_type);
//     cell3.appendChild(q_title);
//     cell4.appendChild(q_body);
//     cell5.appendChild(a);
//     $("#closeQuesModalBtn").click();
// }

// // 問題削除
// function removeQues(question_id) {
//     var ques_str = "";
//     var ques_id_arr = document.getElementById("ques_id_arr").value;
//     let ques_arr = ques_id_arr.split(',');
//     for (var i = 0; i < ques_arr.length; i++) {
//         if (ques_arr[i] != question_id) {
//             if (ques_str == "") {
//                 ques_str = ques_arr[i] + "";
//             } else {
//                 ques_str = ques_str + "," + ques_arr[i];
//             }
//         }
//     }
//     document.getElementById("ques_id_arr").value = ques_str;
//     var row = document.getElementById("ques_" + question_id);
//     row.parentNode.removeChild(row);
// }

// // 対象者を取消
// function selectAccount(id, name, group_id, email) {

//     var acc_id_arr = document.getElementById("acc_id_arr").value;
//     if (acc_id_arr != null && acc_id_arr != '') {
//         let acc_arr = acc_id_arr.split(',');
//         if (acc_arr.includes(id)) {
//             alert("重複している問題があります。");
//             return;
//         } else {
//             acc_id_arr = acc_id_arr + "," + id;
//         }
//     } else {
//         acc_id_arr = id + "";
//     }
//     document.getElementById("acc_id_arr").value = acc_id_arr;

//     var table = document.getElementById("account_tbl").getElementsByTagName('tbody')[0];
//     var row = table.insertRow();
//     row.setAttribute("id", "acc_" + id);
//     var cell1 = row.insertCell();
//     var cell2 = row.insertCell();
//     var cell3 = row.insertCell();
//     var cell4 = row.insertCell();
//     var cell5 = row.insertCell();
//     var a_no = document.createTextNode(table.rows.length - 1);
//     var a_name = document.createTextNode(name);
//     var a_group_id = document.createTextNode(group_id);
//     var a_email = document.createTextNode(email);
//     cell1.appendChild(a_no);
//     cell2.appendChild(a_name);
//     cell3.appendChild(a_group_id);
//     cell4.appendChild(a_email);
//     var a = document.createElement("a");
//     a.text = "取消";
//     a.href = "#";
//     a.addEventListener("click", function () {
//         removeAcc(id)
//     });
//     cell5.appendChild(a);
//     $("#closeAccModalBtn").click();
// }
// function selectAccountUpdate(id, name, group_id, email) {

//     var acc_id_arr = document.getElementById("acc_id_arr").value;
//     if (acc_id_arr != null && acc_id_arr != '') {
//         let acc_arr = acc_id_arr.split(',');
//         if (acc_arr.includes(id)) {
//             alert("重複している問題があります。");
//             return;
//         } else {
//             acc_id_arr = acc_id_arr + "," + id;
//         }
//     } else {
//         acc_id_arr = id + "";
//     }
//     document.getElementById("acc_id_arr").value = acc_id_arr;

//     var table = document.getElementById("account_tbl").getElementsByTagName('tbody')[0];
//     var row = table.insertRow(table.rows.length);
//     row.setAttribute("id", "acc_" + id);
//     var cell1 = row.insertCell();
//     var cell2 = row.insertCell();
//     var cell3 = row.insertCell();
//     var cell4 = row.insertCell();
//     var cell5 = row.insertCell();
//     var a_no = document.createTextNode(table.rows.length);
//     var a_name = document.createTextNode(name);
//     var a_group_id = document.createTextNode(group_id);
//     var a_email = document.createTextNode(email);
//     cell1.appendChild(a_no);
//     cell2.appendChild(a_name);
//     cell3.appendChild(a_group_id);
//     cell4.appendChild(a_email);
//     var a = document.createElement("a");
//     a.text = "取消";
//     a.href = "#";
//     a.addEventListener("click", function () {
//         removeAcc(id)
//     });
//     cell5.appendChild(a);
//     $("#closeAccModalBtn").click();
// }

// // 対象者を取消
// function removeAcc(acc_id) {
//     var acc_str = "";
//     var acc_id_arr = document.getElementById("acc_id_arr").value;
//     let acc_arr = acc_id_arr.split(',');
//     for (var i = 0; i < acc_arr.length; i++) {
//         if (acc_arr[i] != acc_id) {
//             if (acc_str == "") {
//                 acc_str = acc_arr[i] + "";
//             } else {
//                 acc_str = acc_str + "," + acc_arr[i];
//             }
//         }
//     }
//     document.getElementById("acc_id_arr").value = acc_str;
//     var row = document.getElementById("acc_" + acc_id);
//     row.parentNode.removeChild(row);
// }


// チェックボックス判定
$(document).ready(function () {
    // 問題
   // $('#question-remove').prop('disabled', true);
    $('#question-modal-add').prop('disabled', true);
    // 問題一覧
    $('#question-checkbox input:checked').each(function () {
       // $('#question-remove').prop('disabled', false);
        return false;
    });
    // 問題Modal一覧
    $('#question-modal-checkbox input:checked').each(function () {
      //  $('#question-modal-add').prop('disabled', false);
        return false;
    });

    $('input[type="checkbox"]').click(function () {
        // 問題一覧
        var selected = [];
        $('#question-checkbox input:checked').each(function () {
            $('#question-checkbox input:checked').each(function () {
               // $('#question-remove').prop('disabled', false);
                selected.push($(this).attr('name'));
                return false;
            });
        });
        if (selected.length == 0) {
          //  $('#question-remove').prop('disabled', true);
        }
        // 問題Modal一覧
        var selected2 = [];
        $('#question-modal-checkbox input:checked').each(function () {
            $('#question-modal-checkbox input:checked').each(function () {
                $('#question-modal-add').prop('disabled', false);
                selected2.push($(this).attr('name'));
                return false;
            });
        });
        if (selected2.length == 0) {
            $('#question-modal-add').prop('disabled', true);
        }
    });

    //　アカウント
    // アカウント一覧
    $('#account-remove').prop('disabled', true);
    $('#account-checkbox input:checked').each(function () {
        $('#account-remove').prop('disabled', false);
        return false;
    });
    // アカウントModal一覧
    $('#account-modal-add').prop('disabled', true);
    $('#account-modal-checkbox input:checked').each(function () {
        $('#account-modal-add').prop('disabled', false);
        return false;
    });

    $('input[type="checkbox"]').click(function () {
        // アカウント一覧
        var selected = [];
        $('#account-checkbox input:checked').each(function () {
            $('#account-checkbox input:checked').each(function () {
                $('#account-remove').prop('disabled', false);
                selected.push($(this).attr('name'));
                return false;
            });
        });
        if (selected.length == 0) {
            $('#account-remove').prop('disabled', true);
        }
        //　アカウントModal一覧
        var selected2 = [];
        $('#account-modal-checkbox input:checked').each(function () {
            $('#account-modal-checkbox input:checked').each(function () {
                $('#account-modal-add').prop('disabled', false);
                selected2.push($(this).attr('name'));
                return false;
            });
        });
        if (selected2.length == 0) {
            $('#account-modal-add').prop('disabled', true);
        }
    });
});