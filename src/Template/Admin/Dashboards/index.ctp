<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Danh sách Doanh thu Booking</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Đại lý</th>
                                    <th scope="col">Mã Booking</th>
                                    <th scope="col">Khách hàng</th>
                                    <th scope="col">Địa phương</th>
                                    <th scope="col">Khách sạn</th>
                                    <th scope="col">Ngày tạo Booking</th>
                                    <th scope="col">Giá gốc</th>
                                    <th scope="col">Giá bán Đại lý</th>
                                    <th scope="col">Doanh thu</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($bookings as $key => $booking): ?>
                                    <tr>
                                        <td><?= ++$key ?></td>
                                        <td><?= $booking->user->screen_name ?></td>
                                        <td><?= $booking->code ?></td>
                                        <td><?= $booking->full_name ?></td>
                                        <td><?php
                                            if ($booking->type == HOTEL) {
                                                echo $booking->hotel->location->name;
                                            }
                                            if ($booking->type == COMBO) {
                                                echo $booking->combo->destination->name;
                                            }
                                            if ($booking->type == VOUCHER) {
                                                echo $booking->voucher->destination->name;
                                            }
                                            if ($booking->type == LANDTOUR) {
                                                echo $booking->land_tour->destination->name;
                                            }
                                            ?></td>
                                        <td><?php
                                            if ($booking->type == HOTEL) {
                                                echo $booking->hotel->name;
                                            }
                                            if ($booking->type == COMBO) {
                                                if (count($booking->combo->hotels) == 2) {
                                                    foreach ($booking->combo->hotels as $key => $hotel) {
                                                        if ($key == 0) {
                                                            echo $hotel->name . ", ";
                                                        } else {
                                                            echo $hotel->name;
                                                        }
                                                    }
                                                } else {
                                                    echo $hotel->name;
                                                }
                                            }
                                            if ($booking->type == VOUCHER) {
                                                echo $booking->voucher->hotel->name;
                                            }
                                            ?></td>
                                        <td><?= date_format($booking->created, "d/m/Y h:m:s") ?></td>
                                        <td><?php
                                            if ($booking->type == HOTEL) {
                                                echo number_format($booking->price - $booking->revenue - $booking->hotel->price_agency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == COMBO) {
                                                $hotelAgency = 0;
                                                foreach ($booking->combo->hotels as $hotelPrice) {
                                                    $hotelAgency += $hotelPrice->price_agency;
                                                }
                                                echo number_format($booking->price - $booking->revenue - $hotelAgency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == LANDTOUR) {
                                                echo number_format($booking->price - $booking->revenue - $booking->land_tour->trippal_price * $booking->amount);
                                            }
                                            if ($booking->type == VOUCHER) {
                                                echo number_format($booking->price - $booking->revenue - $booking->voucher->trippal_price * $booking->amount * $booking->days_attended->days);
                                            }
                                            ?></td>
                                        <td><?php
                                            if ($booking->type == HOTEL) {
                                                echo number_format($booking->price - $booking->hotel->price_agency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == COMBO) {
                                                $hotelAgency = 0;
                                                foreach ($booking->combo->hotels as $hotelPrice) {
                                                    $hotelAgency += $hotelPrice->price_agency;
                                                }
                                                echo number_format($booking->price - $hotelAgency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == LANDTOUR) {
                                                echo number_format($booking->price - $booking->land_tour->trippal_price * $booking->amount);
                                            }
                                            if ($booking->type == VOUCHER) {
                                                echo number_format($booking->price - $booking->voucher->trippal_price * $booking->amount * $booking->days_attended->days);
                                            }
                                            ?></td>
                                        <td><?php
                                            if ($booking->type == HOTEL) {
                                                echo number_format($booking->hotel->price_agency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == COMBO) {
                                                $hotelAgency = 0;
                                                foreach ($booking->combo->hotels as $hotelPrice) {
                                                    $hotelAgency += $hotelPrice->price_agency;
                                                }
                                                echo number_format($hotelAgency * $booking->days_attended->days * $booking->amount);
                                            }
                                            if ($booking->type == LANDTOUR) {
                                                echo number_format($booking->land_tour->trippal_price * $booking->amount);
                                            }
                                            if ($booking->type == VOUCHER) {
                                                echo number_format($booking->voucher->trippal_price * $booking->amount * $booking->days_attended->days);
                                            }
                                            ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

