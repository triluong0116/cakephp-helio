<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js"></script>
<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.7.0/firebase-firestore.js"></script>
<?php echo $this->Html->script('/frontend/libs/jquery/jquery-3.3.1.min'); ?>
<script type="text/javascript">
    let room = '<?= $chatRoomId ?>';
    // console.log(room);
    // let roomCheck = JSON.parse(room);
    if (room){
        // Initialize Firebase
        // var config = {
        //     apiKey: "AIzaSyCq63i6LAwC6IMmikwWdTW4KP4OnSlsSu4",
        //     authDomain: "mustgoproj.firebaseapp.com",
        //     projectId: "mustgoproj",
        //     storageBucket: "mustgoproj.appspot.com",
        //     messagingSenderId: "800412534543",
        //     appId: "1:800412534543:web:31b037c7928dc208dce07a"
        // };
        const firebaseConfig = {
            apiKey: "AIzaSyDYyzYWKhDXw2-tMNoB4f8-5KzdzRkeJYc",
            authDomain: "newproject-a44dd.firebaseapp.com",
            projectId: "newproject-a44dd",
            storageBucket: "newproject-a44dd.appspot.com",
            messagingSenderId: "553067709280",
            appId: "1:553067709280:web:df89a52056196d38c1d405",
            measurementId: "G-Z6X9JLJ2M0"
        };
        firebase.initializeApp(firebaseConfig);
        // Initialize Cloud Firestore through Firebase
        var db = firebase.firestore();
        // Disable deprecated features
        db.settings({
            timestampsInSnapshots: true
        });
        var chat_room_id = room;
    }
    else {
        var chat_room_id = [];
    }
    let user = "<?= $userId ?>";
    if (user != null){
        var current_u_id = "<?= $userId ?>";
    } else {
        var current_u_id = "";
    }
    var current_s_id = "<?= $saleAdmin ? $saleAdmin['id'] : '' ?>";

    if (chat_room_id.length > 0) {
        console.log('iframe here');
        // db.collection('chatroom').doc(chat_room_id).collection('messages').get()
        //     .then((querySnapshot) => {
        //         querySnapshot.forEach((doc) => {
        //             // doc.data() is never undefined for query doc snapshots
        //             console.log(doc.id, " => ", doc.data());
        //         });
        //     })
        //     .catch((error) => {
        //         console.log("Error getting documents: ", error);
        //     });
        // db.collection('chatroom').doc(chat_room_id).collection('messages').onSnapshot((querySnapshot) => {
        //     querySnapshot.forEach((doc) => {
        //         // doc.data() is never undefined for query doc snapshots
        //         // console.log(doc.id, " => ", doc.data());
        //         if (doc.data().createdBy == current_u_id ){
        //             let newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
        //                 '                        <div class="message-guest">\n' +
        //                 '                            <p>' + doc.data().text + '\n' +
        //                 '                        </div>\n' +
        //                 '                    </div>');
        //             $('.content-message .row').append(newChatEle);
        //         } else {
        //             let newChatEle = $('<div class="col-sm-36" id="' + doc.data().createdAt + '">\n' +
        //                 '                        <div class="message-admin">\n' +
        //                 '                            <p>' + doc.data().text + '\n' +
        //                 '                        </div>\n' +
        //                 '                    </div>');
        //             $('.content-message .row').append(newChatEle);
        //         }
        //     });
        //     $('.list-chat').scrollTop($('.list-chat')[0].scrollHeight);
        // });
        // db.collection('chatroom').doc(chat_room_id).onSnapshot(
        //     (doc) => {
        //         if ((doc.data().is_read) == 0 && doc.data().latestMessage.createdBy != current_u_id) {
        //             if ($('#' + doc.data().latestMessage.createdAt).length == 0) {
        //                 $('#icon-notify').removeClass('d-none');
        //             }
        //         }
        //         if ((doc.data().is_read) == 1) {
        //             $('#icon-notify').addClass('d-none');
        //         }
        //     }
        // );
    }
    // console.log($('#MainPopupIframe'));
    // $('#MainPopupIframe').on('load', function(){
    //     console.log('load done');
    // });
    function checkIframeLoaded() {
        // Get a handle to the iframe element
        var iframe = document.getElementById('MainPopupIframe');
        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        // Check if loading is complete
        if (  iframeDoc.readyState  == 'complete' ) {
            //iframe.contentWindow.alert("Hello");
            iframe.contentWindow.onload = function(){
                alert("I am loaded");
            };
            // The loading is complete, call the function we want executed once the iframe is loaded
            afterLoading();
            return;
        }

        // If we are here, it is not loaded. Set things up so we check   the status again in 100 milliseconds
        window.setTimeout(checkIframeLoaded, 100);
    }

    function afterLoading(){
        let html = document.getElementById("MainPopupIframe").contentWindow.document.body.innerHTML;
        let title = document.getElementById("MainPopupIframe").contentDocument.title;
        document.title = title;
        window.history.pushState({"html":html,"pageTitle":title},"", document.getElementById("MainPopupIframe").contentWindow.location.href);
        // alert();
    }
    document.getElementById('checkIframeLoaded').contentWindow.location.reload();
</script>
<iframe onload="checkIframeLoaded()" src="<?= \Cake\Routing\Router::url(['_name' => 'home']) ?>" id="MainPopupIframe" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
    Your browser doesn't support iframes
</iframe>
