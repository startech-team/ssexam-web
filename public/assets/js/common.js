$('.modal').on('shown.bs.modal', function (e) {
    $('.modal.show').each(function (index) {
        $(this).css('z-index', 1101 + index * 2);
    });
    $('.modal-backdrop').each(function (index) {
        $(this).css('z-index', 1101 + index * 2 - 1);
    });
});

// フォーム送信にEnterキーのブロック
// $(document).ready(function() {
//     $('form').on('keypress', function(e) {
//         var code = e.keyCode || e.which;
//         var type = 'localName' in e.target ? e.target.localName : ''
//         if(code == 13 && type != 'textarea'){
//             e.preventDefault();
//         }   
//     });    
// });
