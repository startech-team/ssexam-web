// function setBrowserState(exam_id) {
//     history.pushState("", "", "exam-detail/" + exam_id);
// }

// history.pushState("1", "", "");

// window.addEventListener('popstate', function (event) {

//     var title = $(document).find("title").text();
//     if(title == "SS-EXAM(Detail)") {
//         const leavePage = confirm("試験を終了しますか？");
//         if (leavePage) {
//             document.getElementById('exam-commit-form').submit();
//         } else {
//             return false;
//         }
//     }
// });