<div class="container mt50 mb30">
    <div class="car_search">
        <div id="app"></div> <!--đặt thẻ này ở nơi muốn hiển thị form-->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.12.1/jquery-ui.js"></script>
<script src="https://taxiairport.vn/js/dichung_booking_iframe.min.js"></script>
<script>
    $(document).ready(function () {
        $('#app').DichungBooking({
            showSpecialRequest: false, //Hiển thị tab Yêu cầu đặc biệt
            lang: 'vi', //Ngôn ngữ hiển thị
            partnerDomain: 'mustgo', //Domain partner, đây là thông tin cung cấp bởi Đi chung, khai báo đúng mới record được booking về tài khoản
            showLabel: false, //Hiển thị nhãn các trường nhập liệu
            width: '100%', //Độ rộng của booking form
            textColor: '#333', //Mã màu html của Text form
            majorColor: '#126bc4', // Màu của CTA button
            fontSize: '14px',
        });
    });
</script>
