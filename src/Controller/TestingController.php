<?php

namespace App\Controller;

use Cake\Log\Log;
use Cake\Utility\Xml;
use App\Controller\AppController;
use Cake\Event\Event;

class TestingController extends AppController
{
    public function testApi()
    {
        libxml_use_internal_errors(true);
        $this->loadModel('Hotels');
        $data = $this->request->input();
        $xmlArray = Xml::toArray(Xml::build($data));
        Log::write('debug', 'Request sen testapi haha : '.$data);
        $username = isset($xmlArray['Envelope']['SOAP-ENV:Header']['wsse:Security']['wsse:UsernameToken']['wsse:Username']) ?  $xmlArray['Envelope']['SOAP-ENV:Header']['wsse:Security']['wsse:UsernameToken']['wsse:Username'] : '';
        $password = isset($xmlArray['Envelope']['SOAP-ENV:Header']['wsse:Security']['wsse:UsernameToken']['wsse:Password']['@']) ? $xmlArray['Envelope']['SOAP-ENV:Header']['wsse:Security']['wsse:UsernameToken']['wsse:Password']['@'] : '';
        $bodyReq = $xmlArray['Envelope']['SOAP-ENV:Body'];
        $now = date('Y-m-d').'T'.date('H:m:s').'+07:00';
        $bodyRes = '';
        if ($username == 'testMustgo' && $password == 'mustgo.vn'){
            foreach ($bodyReq['OTA_HotelAvailRQ']['AvailRequestSegments'] as $val ){
                $idHotel = str_replace('MHcode','',$val['HotelSearchCriteria']['Criterion']['HotelRef']['@HotelCode']);
                $hotel = $this->Hotels->find()->contain(['Rooms'])->where(['id' => $idHotel])->first();
                $bodyResRoomStay = '';
                if ($hotel){
                    foreach ($hotel->rooms as $item){
                        $bodyResRoomStay .= '
                <RoomStay>
			<RoomTypes>
				<RoomType RoomTypeCode="'.$item->slug.'">
					<RoomDescription Name="'.$item->name.'">
						<Text>'.$item->description.'</Text>
					</RoomDescription>
				</RoomType>
			</RoomTypes>
			<RatePlans>
				<RatePlan RatePlanCode="'.$item->id.'">
					<RatePlanDescription Name="Best Available Rate">
						<Text>Best available rate including breakfast.</Text>
					</RatePlanDescription>
				</RatePlan>
			</RatePlans>
		</RoomStay>
                ';
                    }
                    $bodyRes = '
                <OTA_HotelAvailRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TimeStamp="'.$now.'" EchoToken="'.$bodyReq['OTA_HotelAvailRQ']['@EchoToken'].'">
	            <Success/>
	             <RoomStays>
	            '.$bodyResRoomStay.'
                </RoomStays>
            </OTA_HotelAvailRS>
                ';
                } else{
                    $bodyRes = '
            <OTA_HotelAvailRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TimeStamp="'.$now.'" EchoToken="'.$bodyReq['OTA_HotelAvailRQ']['@EchoToken'].'">
	            <Errors>
		            <Error Type="6" Code="392" >Cannot find hotelier with code '.$val['HotelSearchCriteria']['Criterion']['HotelRef']['@HotelCode'].'</Error>
	            </Errors>
            </OTA_HotelAvailRS>
            ';
                }
            }
        }
        else{
            $bodyRes = '
            <OTA_HotelAvailRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0" TimeStamp="'.$now.'" EchoToken="'.$bodyReq['OTA_HotelAvailRQ']['@EchoToken'].'">
	            <Errors>
		            <Error Type="4">Your username or password is incorrect</Error>
	            </Errors>
            </OTA_HotelAvailRS>
            ';
        }
        $string = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
	<SOAP-ENV:Header>
		<wsse:Security soap:mustUnderstand="1"
			xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"
			xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<wsse:UsernameToken>
				<wsse:Username>'.$username.'</wsse:Username>
				<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.$password.'</wsse:Password>
			</wsse:UsernameToken>
		</wsse:Security>

	</SOAP-ENV:Header>

	<SOAP-ENV:Body>
		'.$bodyRes.'
	</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';
        $output = $this->response;
        $output = $output->withType('text/xml');
        $output = $output->withCharset('utf-8');
        $output = $output->withStringBody($string);
        return $output;
    }
}
