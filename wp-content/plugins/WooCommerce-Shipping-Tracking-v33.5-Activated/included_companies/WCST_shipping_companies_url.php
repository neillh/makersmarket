<?php

class WCST_shipping_companies_url
{
	public static function get_company_url($trackurl,$trackno, $shipping_postcode = '', $order = null)
	{
		global $wcst_order_model;
		
		//Old method
		$shipping_postcode = str_replace(" ","",$shipping_postcode);
		$urltrack = ctype_lower($trackno) ? $this->get_from_old_data_structure($order, $trackno, $shipping_postcode) : null;
		
			
		//Custom companies	
		if($urltrack == null)
		{
			$urltrack = "";
			$custom_companies = get_option( 'wcst_user_defined_companies');
			$country_code = isset($order) ? $order->get_shipping_country() : "";
			if(isset($custom_companies) && is_array($custom_companies))
				foreach ($custom_companies as $index => $company_info) //['name'] and ['url']
					if ($index  == $trackurl ) //Custom company detected
					{
						$codes = explode(",", $trackno);
						$urltrack = $company_info['url'];
						foreach($codes as $code)
						{
							$code = trim($code);
							$urltrack =  preg_replace('/%s/', $code, $urltrack, 1);
							$urltrack =  preg_replace('/%S/', $code, $urltrack, 1);
							$urltrack =  preg_replace('/\s+/', '', $urltrack); //remove spaces
							$urltrack =  preg_replace('/%country_code/',  $country_code, $urltrack); 
							$urltrack =  preg_replace('/%post_code/',  $shipping_postcode, $urltrack); //remove spaces
						}
					}
		}
		if($urltrack == null) //is still null means that the $trackurl refers to an AfterShip id 
		{
			$lang = $wcst_order_model->get_lang($order);
			$lang = $lang != "en" ? $lang : "";
			$urltrack = "https://www.aftership.com/{$lang}/track/{$trackurl}/{$trackno}";
			//17track: $urltrack = "https://t.17track.net/{$lang}/#nums={$trackno}&fc={$trackurl}";
		}
			
		$urltrack = apply_filters('wcst_getting_tracking_url', $urltrack, $trackurl, $trackno, $shipping_postcode, $order);
		return array("urltrack"=> $urltrack);
	}
	public function get_from_old_data_structure($order, $trackno, $shipping_postcode)
	{
		$urltrack = null;
		if ($trackurl == 'VIETNAMPOST'){
			$urltrack = "http://www.vnpost.vn/TrackandTrace/tabid/130/n/{$trackno}/t/0/s/1/Default.aspx";
		} 
		else if ($trackurl == 'USPS'){
			$urltrack = 'https://tools.usps.com/go/TrackConfirmAction_input?strOrigTrackNum='.$trackno;
		} 
		else if ($trackurl == 'AUSTRALIAPOST'){
			$urltrack = 'http://auspost.com.au/track/display.asp?type=article&id='.$trackno;	 
		}
		else if ($trackurl == 'AUSTRALIAPOSTINTL'){
			$urltrack = 'https://auspost.com.au/mypost/track/#/details/'.$trackno;
		}
		else if ($trackurl == 'CANADAPOST'){
			$urltrack = 'https://www.canadapost-postescanada.ca/track-reperage/en#/search?searchFor=='.$trackno;
		}
		else if ($trackurl == 'CHILEXPRESS'){
			$urltrack = 'https://centrodeayuda.chilexpress.cl/seguimiento/'.$trackno;
		}
		else if ($trackurl == 'STARKEN'){
			$urltrack = 'https://www.starken.cl/seguimiento?codigo='.$trackno;
		}
		else if ($trackurl == 'TNTCHILE'){
			$urltrack = 'https://clsclweb.tntchile.cl/txapgw/tracking.asp?boleto='.$trackno;
		}
		else if ($trackurl == 'HKPOST'){
			$urltrack = 'https://track.aftership.com/hong-kong-post/'.$trackno;
		}
		else if ($trackurl == 'ANPOST'){
			$urltrack = 'http://track.anpost.ie/track/track.asp?track='.$trackno;
		}
		else if ($trackurl == 'PARCELFORCE'){
			$urltrack = 'http://www.parcelforce.com/track-trace?trackNumber='.$trackno.'&page_type=rml-tracking-details';
		}
		else if ($trackurl == 'FEDEX'){
			$urltrack = 'https://www.fedex.com/fedextrack/?action=track&tracknumbers='.$trackno;
		}
		else if ($trackurl == 'DHL'){
			$urltrack = 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB='.$trackno;
		}
		else if ($trackurl == 'DHLGLOBALMAIL'){
			$urltrack = 'https://webtrack.dhlglobalmail.com/?trackingnumber='.$trackno;
		}
		else if ($trackurl == 'UPS'){
			$urltrack = 'http://wwwapps.ups.com/WebTracking/processRequest?&tracknum='.$trackno;
		}
		else if ($trackurl == 'GLSEUROPE'){
			$urltrack = 'https://gls-group.eu/EU/en/parcel-tracking?match='.$trackno;
		}
		else if ($trackurl == 'GLSITALIA'){
			$urltrack = 'https://www.gls-italy.com/?option=com_gls&view=track_e_trace&mode=search&numero_spedizione='.$trackno.'&tipo_codice=nazionale';
		}
		else if ($trackurl == 'GLSSPAIN'){
			$urltrack = "https://mygls.gls-spain.es/e/{$trackno}/{$shipping_postcode}";
		}
		else if ($trackurl == 'GLSGERMANY'){
			$urltrack = "https://www.gls-pakete.de/sendungsverfolgung?match={$trackno}";
		}
		else if ($trackurl == 'POSTNLINTL')
		{
			if($order)
				$urltrack = 'https://jouw.postnl.nl/track-and-trace/'.$trackno.'-'.$order->get_shipping_country().'-'.$shipping_postcode;
			else 
				$urltrack = 'https://jouw.postnl.nl/track-and-trace/'.$trackno.'/NL/'.$shipping_postcode;
		}
		else if ($trackurl == 'POSTNLL')
		{
			$urltrack = 'https://jouw.postnl.nl/#!/track-en-trace/'.$trackno.'/NL/'.$shipping_postcode;
		}
		else if ($trackurl == 'COURIERPOST'){
			$urltrack = 'http://trackandtrace.courierpost.co.nz/search/'.$trackno;
		}
		else if ($trackurl == 'NEWZEALANDPOST'){
			$urltrack = 'http://www.nzpost.co.nz/tools/tracking?trackid='.$trackno;
		}
		else if ($trackurl == 'FASTWAY'){
			$urltrack = 'http://fastway.com.au/courier-services/track-your-parcel?l='.$trackno;
		}
		else if ($trackurl == 'FASTWAYNZ'){
			$urltrack = 'http://fastway.co.nz/courier-services/track-your-parcel?l='.$trackno;
		}
		else if ($trackurl == 'TPCINDIA'){
			$urltrack = 'http://www.tpcindia.com/track.aspx?id='.$trackno;
		}
		else if ($trackurl == 'TRADELL'){
			$urltrack = 'http://www.tradelinkinternational.co.in/track.asp?awbno='.$trackno;
		}
		else if ($trackurl == 'OMICC'){
			$urltrack = 'http://www.omintl.net/tracking.aspx?AwbNo='.$trackno;
		}
		else if ($trackurl == 'ICCW'){
			$urltrack = 'http://www.iccworld.com/track.asp?txtawbno='.$trackno;
		}
		else if ($trackurl == 'UACE'){
			$urltrack = 'http://urgentair.co.in/trackshipment_status.php?track='.$trackno;
		}
		else if ($trackurl == 'FIRSTFLIGHT'){
			$urltrack = 'http://www.firstflight.net/track.asp?txtcon_no='.$trackno;
		}
		else if ($trackurl == 'FLASHEXPRESS'){
			$urltrack = 'https://www.flashexpress.com/tracking/?se='.$trackno;
		}
		else if ($trackurl == 'ORBITWW'){
			$urltrack = 'http://www.orbitexp.com/tools/showTrack.asp?awbnoMul='.$trackno;
		}
		else if ($trackurl == 'FLYKING'){
			$urltrack = 'http://www.flykingonline.com/WebFCS/cnotequery.aspx?cnoteno='.$trackno;
		}
		else if ($trackurl == 'SHREEMC'){
			$urltrack = 'http://erp.shreemarutionline.com/frmTrackingDetails.aspx?id='.$trackno;
		}
		else if ($trackurl == 'SMCS'){
			$urltrack = 'http://www.smcouriers.com/Tracking.aspx?btnchk=A&txtAwb='.$trackno;
		}
		else if ($trackurl == 'OVERSEASCS'){
			$urltrack = 'https://webcsw.ocs.co.jp/csw/ECSWG0201R00003P.do?edtAirWayBillNo='.$trackno;
		}
		else if ($trackurl == 'BLUEDART'){
			$urltrack = 'https://www.aftership.com/track/bluedart/'.$trackno;
		}
		else if ($trackurl == 'AFLWIZ'){
			$urltrack = 'http://trackntrace.aflwiz.com/Wiz_Summary.jsp?shpntnum='.$trackno;
		}
		else if ($trackurl == 'AFLLT'){
			$urltrack = 'http://trackntrace.afllogistics.com/login.do?gcn='.$trackno;
		}
		else if ($trackurl == 'BLAZEFLASHD'){
			$urltrack = 'http://www.blazeflash.net/trackdetail.aspx?awbno='.$trackno;
		}
		else if ($trackurl == 'BLAZEFLASHI'){
			$urltrack = 'http://www.blazeflash.net/intl/trackfinal.asp?search='.$trackno;
		}
		else if ($trackurl == 'ARAMEX'){
			$urltrack = 'https://www.aramex.com.au/tools/track?l='.$trackno;
		}
		else if ($trackurl == 'SHREEMAHAC'){
			$urltrack = 'http://www.shreemahavircourier.com/ShipmentDetails.aspx?Type=track&awb='.$trackno;
		}
		else if ($trackurl == 'POSTOUK'){
			$urltrack = 'http://www.postoffice.co.uk/track-trace?trackNumber='.$trackno.'&page_type=rml-tracking-details';
		}
		else if ($trackurl == 'TNTEXPRESS'){
			$urltrack = 'http://www.tnt.com/webtracker/tracking.do?cons='.$trackno.'&trackType=CON&saveCons=Y';
		}
		else if ($trackurl == 'HDNL'){
			$urltrack = 'http://www.hdnl.co.uk/UPI-Tracking-Details/?upi='.$trackno;
		}
		else if ($trackurl == 'CITYLINK'){
			$urltrack = 'http://www.city-link.co.uk/dynamic/track.php?parcel_ref_num='.$trackno;
		}
		else if ($trackurl == 'JPPOST'){
			$urltrack = 'http://tracking.post.japanpost.jp/service/singleSearch.do?searchKind=S004&locale=en&reqCodeNo1='.$trackno.'&x=16&y=15';
		}
		else if ($trackurl == 'POSTDAN'){
			$urltrack = 'http://www.postdanmark.dk/tracktrace/TrackTrace.do?i_lang=INE&i_stregkode='.$trackno;
		}
		else if ($trackurl == 'POSTSWEDEN'){
			$urltrack = 'https://www.postnord.se/online-verktyg/verktyg/sok/spara-brev-paket-och-pall#dynamicloading=true&shipmentid='.$trackno;
		}
		else if ($trackurl == 'POSTNORWAY'){
			$urltrack = 'http://sporing.posten.no/sporing.html?q='.$trackno.'&lang=en';
		}
		else if ($trackurl == 'PARCEL2GO'){
			$urltrack = 'https://www.parcel2go.com/UniversalTracking.aspx?tk='.$trackno;
		}
		else if ($trackurl == 'YODEL'){
			$urltrack = 'https://www.yodel.co.uk/tracking/'.$trackno.'/'.$shipping_postcode;
		}
		else if ($trackurl == 'COLLECTPLUS'){
			$urltrack = 'https://www.collectplus.co.uk/track/'.$trackno;
		}
		else if ($trackurl == 'CITYSPRINT'){
			$urltrack = 'http://ijb.citysprint.co.uk/cs/quiktrak.php?CK=&wwhawb='.$trackno;
		}
		else if ($trackurl == 'POSTINDIA'){
			$urltrack = 'https://track.aftership.com/india-post/'.$trackno;
		}
		else if ($trackurl == 'INTEXPRESS'){
			$urltrack = 'http://www.interlinkexpress.com/tracking/trackingSearch.do?search.searchType=0&appmode=guest&search.parcelNumber='.$trackno;
		}
		else if ($trackurl == 'DPDPARCEL'){
			$urltrack = 'https://tracking.dpd.de/status/en_US/parcel/'.$trackno;
		}
		else if ($trackurl == 'SPEEDEE'){
			$urltrack = 'http://packages.speedeedelivery.com/packages.asp?tracking='.$trackno;
		}
		else if ($trackurl == 'PUROLATOR'){
			$urltrack = 'https://eshiponline.purolator.com/ShipOnline/Public/Track/TrackingDetails.aspx?pup=Y&pin='.$trackno;
		}
		else if ($trackurl == 'ONTRAC'){
			$urltrack = 'http://www.ontrac.com/trackingres.asp?tracking_number='.$trackno.'&x=16&y=8';
		}
		else if ($trackurl == 'LASERSHIP'){
			$urltrack = 'https://t.lasership.com/Track/'.$trackno;
		}
		else if ($trackurl == 'SAFEX'){
			$urltrack = 'http://www.safexpress.com/shipment_inq.aspx?sno='.$trackno;
		}
		else if ($trackurl == 'DYNAMEX'){
			$urltrack = 'https://www.dynamex.com/shipping/dxnow-order-track?ctl='.$trackno;
		}
		else if ($trackurl == 'ENSENDA'){
			$urltrack = 'http://www.ensenda.com/content/track-shipment?trackingNumber='.$trackno.'&TRACKING_SEND=GO';
		}else if ($trackurl == 'ESTAFETA'){
			$urltrack = 'https://rastreositecorecms.azurewebsites.net/Tracking/searchByGet/?wayBillType=0&wayBill='.$trackno;
		}
		else if ($trackurl == 'CEVA'){
			$urltrack = 'http://www.cevalogistics.com/en-US/toolsresources/Pages/CEVATrak.aspx?sv='.$trackno;
		}
		else if ($trackurl == 'AONEINT'){
			$urltrack = 'http://www.aoneonline.com/pages/customers/shiptrack.php?tracking_number='.$trackno;
		}
		else if ($trackurl == 'PARCELLINK'){
			$urltrack = 'http://www.parcel-link.co.uk/track-and-trace.php?consignment='.$trackno;
		}
		else if ($trackurl == 'NAPAREX'){
			$urltrack = 'https://xcel.naparex.com/orders/WebForm/OrderTracking.aspx?OrderTrackingID='.$trackno;
		}
		else if ($trackurl == 'PNCOURIER'){
			$urltrack = 'https://www.poslaju.com.my/track-trace-v2/';
		}
		else if ($trackurl == 'SKYNET'){
			$urltrack = 'http://www.courierworld.com/scripts/webcourier1.dll/TrackingResultwoheader?type=4&nid=1&hawbNoList='.$trackno;
		}
		else if ($trackurl == 'GDEX')
		{
			$urltrack = 'https://web3.gdexpress.com/official/iframe/etracking_v4.php?input='.$trackno."&choice=cnGdex";
		}
		else if ($trackurl == 'CHRONOS'){
			$urltrack = 'http://chronoscouriers.com/popup/scr_popup_trak_shipment.php?shipmentId='.$trackno;
		}
		else if ($trackurl == 'POSMALAY'){
			$urltrack = 'https://tracking.pos.com.my/tracking/'.$trackno;
		}
		else if ($trackurl == 'LAPOSTE'){
			$urltrack = 'https://www.laposte.fr/outils/track-a-parcel?code='.$trackno;
		}
		else if ($trackurl == 'JNEEXP')
		{
			$urltrack = 'https://track.aftership.com/jne/'.$trackno;
		}
		else if ($trackurl == 'BRTCE'){
			$urltrack = 'http://as777.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&Nspediz='.$trackno.'&RicercaNumeroSpedizione=Search';
		}
		else if ($trackurl == 'ROYALMAIL')
		{
			$urltrack = 'http://www.royalmail.com/portal/rm/track?trackNumber='.$trackno;
		}
		else if ($trackurl == 'MONDRELAY')
		{
			$urltrack = 'https://www.mondialrelay.fr/suivi-de-colis?codeMarque=EC&numeroExpedition='.$trackno.'&pays=FR&language=frn';
		}
		else if ($trackurl == 'MYHERMESEU')
		{
			$urltrack = 'https://www.hermesworld.com/en/our-services/distribution/parcel-delivery/parcel-tracking/?trackingNo='.$trackno;
		}
		else if ($trackurl == 'MYHERMES'){
			$urltrack = 'https://www.myhermes.co.uk/tracking-results.html?trackingNumber='.$trackno;
		}
		else if ($trackurl == 'SINGPOST'){
			$urltrack = 'https://track.aftership.com/singapore-post/'.$trackno;
		}
		else if ($trackurl == 'GATI'){
			$urltrack = 'http://www.gati.com/single_dkt_track_int.jsp?dktNo='.$trackno;
		}
		else if ($trackurl == 'AFGHANPOST'){
			$urltrack = 'http://track.afghanpost.gov.af/index.php?ID='.$trackno;
		}
		else if ($trackurl == 'PAKPOST'){
			$urltrack = 'http://ep.gov.pk/track.asp?textfield='.$trackno;
		}
		else if ($trackurl == 'LITPOST'){
			$urltrack = 'https://post.lt/siuntu-sekimas?parcels='.$trackno;
		}
		else if ($trackurl == 'LPEXPRESS'){
			$urltrack = 'https://post.lt/siuntu-sekimas?parcels='.$trackno;
		}
		else if ($trackurl == 'OMNIVA'){
			$urltrack = 'https://mano.omniva.lt/track/'.$trackno;
		}
		else if ($trackurl == 'PERUPOST'){ //No longer exists
			$urltrack = 'http://clientes.serpost.com.pe/Web-Original/IPSWeb_item_events.asp?itemid='.$trackno.'&Submit=Submit';
		}
		else if ($trackurl == 'SERPOST'){ //No tracking url
			$urltrack = 'http://clientes.serpost.com.pe/Web-Original/IPSWeb_item_events.asp?itemid='.$trackno.'&Submit=Submit';
		} 
		else if ($trackurl == 'ROMPOST'){
			$urltrack = 'http://www.posta-romana.ro/en/posta-romana/servicii-online/track-trace.html?track='.$trackno;
		}
		else if ($trackurl == 'ELTA'){
			$urltrack = 'https://www.elta.gr/en-us/personal/tracktrace.aspx?qc='.$trackno;
		}
		else if ($trackurl == 'LBCEX'){
			$urltrack = 'http://www.lbcexpress.com/IN/TrackAndTraceResults/0/'.$trackno;
		}
		else if ($trackurl == 'PHLPOST'){
			$urltrack = 'http://webtrk1.philpost.org/index.asp?i='.$trackno;
		}
		else if ($trackurl == 'UKMAIL'){
			$urltrack = 'https://www.ukmail.com/ConsignmentStatus/UnsecuredConsignmentDetails.aspx?SearchType=Consignment&SearchString='.$trackno;
		}
		else if ($trackurl == 'CORREIOS'){
			$urltrack = 'https://track.aftership.com/brazil-correios/'.$trackno;
		}
		else if ($trackurl == 'CORREIOSCL'){
			$urltrack = 'https://www.correos.cl/web/guest/seguimiento-en-linea?codigos='.$trackno;
		}
		else if ($trackurl == 'CTT'){
			$urltrack = 'https://track.aftership.com/portugal-ctt/'.$trackno;
		}
		else if ($trackurl == 'SMARTSEND'){
			$urltrack = 'https://www.smartsend.com.au/#!track?consignment='.$trackno;
		}
		else if ($trackurl == 'CHRONOEXPRES'){
			$postalcode = str_replace(array('=','=',' '),'',$shipping_postcode);
			$urltrack = 'https://www.chronoexpres.com/web/chronoexpres/envios4#https://www.chronoexpres.com/chronoExtraNET/seguimientos/envios/seguimientoPublicoReq.seam?refEnvio='.$trackno.'&cpDestEnvio='.$postalcode;
		}
		else if ($trackurl == 'ATSHEALTHCARE'){
			$urltrack = 'http://www.atshealthcare.ca/quickTrackResult.aspx?ship='.$trackno;
		}
		else if ($trackurl == 'CANPAR'){
			$postalcode = str_replace(array('=','=',' '),'',$shipping_postcode);
			$urltrack = 'http://www.canpar.com/en/track/TrackingAction.do?locale=en&type=2&reference='.$trackno.'&shipper_num='.$options['CANPARSCODE'];
		}
		else if ($trackurl == 'COLISSIMO'){
			$urltrack = 'https://www.laposte.fr/outils/suivre-vos-envois?code='.$trackno;
		}
		else if ($trackurl == 'CORREOARGENTINO'){
			$urltrack = 'http://www.correoargentino.com.ar/seguimiento_envios/consultar/ondnc/CP/'.$trackno.'/AR';
		}
		else if ($trackurl == 'CORREOSESPAGNA'){
			$urltrack = 'http://www.correos.es/ss/Satellite/site/aplicacion-4000003383089-localiza_busca_encuentra/detalle_app-sidioma=es_ES?numero='.$trackno;
		}
		else if ($trackurl == 'ATSCA'){
			$urltrack = 'http://atssolutions.ca/quickTrackResult.aspx?ship='.$trackno;
		}
		else if ($trackurl == 'OCA'){
			$urltrack = 'https://www1.oca.com.ar/OEPTrackingWeb/detalleenviore.asp?numero='.$trackno;
		}
		else if ($trackurl == 'SELEKTVRACHT'){
			$urltrack = 'http://www.selektvracht.nl/track-and-trace.shtml?bcode='.$trackno;
		}
		else if ($trackurl == 'DHLFORYOU'){
			$urltrack = 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?idc='.$trackno;
		}
		else if ($trackurl == 'TCOURGUY'){
			$urltrack = 'https://www.thecourierguy.co.za/tracking_results.php?WaybillNumber='.$trackno;
		}else if ($trackurl == 'TCAT'){
			$urltrack = 'http://www.t-cat.com.tw/Inquire/TraceDetail.aspx?BillID='.$trackno.'&ReturnUrl=Trace.aspx';
		}
		else if ($trackurl == 'SAPO'){
			$urltrack = 'http://sms.postoffice.co.za/SapoTrackNTrace/TrackNTrace.aspx?id='.$trackno;
		}
		else if ($trackurl == 'DELHIVERY')
		{
			$urltrack = 'https://www.delhivery.com/track/package/'.$trackno;
		}else if ($trackurl == 'DPDFRANCE'){
			$urltrack = 'https://www.dpd.fr/trace/'.$trackno;
		}
		else if ($trackurl == 'DPDIE'){
			$urltrack = 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/'.$trackno.'/Default.aspx';
		}
		else if ($trackurl == 'DHLGER'){
			$urltrack = 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc='.$trackno.'&extendedSearch=true';
		}
		else if ($trackurl == 'DHLDE'){
			$urltrack = 'https://www.dhl.de/de/privatkunden/dhl-sendungsverfolgung.html?piececode='.$trackno;
		}
		else if ($trackurl == 'UPSGER'){
			$urltrack = 'http://wwwapps.ups.com/WebTracking/processRequest?loc=de_DE&tracknum='.$trackno;
		}
		else if ($trackurl == 'DANFRA'){
			$urltrack = 'http://tnt.fragt.dk/Servlet/GetData?fbnr='.$trackno.'&x=19&y=14';
		}
		else if ($trackurl == 'GLSDEN'){
			$urltrack = 'http://www.gls-group.eu/276-I-PORTAL-WEB/content/GLS/DK01/DA/5004.htm?txtRefNo='.$trackno.'&txtAction=71000';
		}
		else if ($trackurl == 'TOLLAU'){
			$urltrack = 'https://online.toll.com.au/trackandtrace/traceConsignments.do?consignments='.$trackno;
		}
		else if ($trackurl == 'INTERPARCEL'){
			$urltrack = 'http://www.interparcel.com.au/tracking.php?action=dotrack&trackno='.$trackno;
		}
		
		else if ($trackurl == 'AUSTRIAPOST'){
			$urltrack = 'http://www.post.at/en/track_trace.php?pnum1='.$trackno;
		}
		else if ($trackurl == 'HAYPOST'){
			$urltrack = 'http://www.haypost.am/view-lang-eng-getemsdata-page.html?itemid='.$trackno;
		}
		else if ($trackurl == 'BELARUSPOST'){
			$urltrack = 'https://www.belpost.by/Otsleditotpravleniye?number='.$trackno;
		}
		else if ($trackurl == 'BELGIUMPOST'){
			$urltrack = 'https://track.bpost.cloud/btr/web/#/search?lang=nl&itemCode='.$trackno."&postalCode=".$shipping_postcode;
		}
		else if ($trackurl == 'BULGARIANPOST'){
			$urltrack = 'http://www.bgpost.bg/IPSWebTracking/IPSWeb_item_events.asp?itemid='.$trackno;
		}
		else if ($trackurl == 'CZECHPOST'){
			$urltrack = 'http://www.ceskaposta.cz/en/nastroje/sledovani-zasilky.php?go=ok&barcode='.$trackno;
		}
		else if ($trackurl == 'FINLANDPOST'){
			$urltrack = 'http://www.posti.fi/itemtracking/posti/search_by_shipment_id?ShipmentId='.$trackno;
		}
		else if ($trackurl == 'CHRONOPOSTFR'){
			$urltrack = 'https://www.chronopost.fr/tracking-no-cms/suivi-page?listeNumerosLT='.$trackno;
		}
		else if ($trackurl == 'TEMANDO'){
			$urltrack = 'https://www.temando.com/education-centre/support/track-your-item?token='.$trackno;
		}
		else if ($trackurl == 'SEUR'){
			$urltrack = 'http://www.seur.com/es/seguimiento-online.do?segOnlineIdentificador='.$trackno;
		}
		else if ($trackurl == 'CHINAPOST'){
			$urltrack = 'http://intmail.183.com.cn/';
		}
		else if ($trackurl == 'DEUTSCHEPOST'){
			$urltrack = 'https://www.deutschepost.de/sendung/simpleQuery.html?locale=en_GB';
		}
		else if ($trackurl == 'PBTNZ'){
			$urltrack = 'http://www.pbt.co.nz/default.aspx';
		}
		else if ($trackurl == 'THAIPOST'){
			$urltrack = 'http://track.thailandpost.co.th/trackinternet/Default.aspx';
		}
		else if ($trackurl == 'POSTEIT'){
			$urltrack = 'https://www.poste.it/cerca/index.html#/risultati-spedizioni/'.$trackno;
		}
		else if ($trackurl == 'ISRAELPOST'){
			$urltrack = 'http://www.israelpost.co.il/itemtrace.nsf/mainsearch';
		}
		else if ($trackurl == 'RUPOST'){
			$urltrack = 'https://www.pochta.ru/tracking#'.$trackno;
		}
		else if ($trackurl == 'TRACKON'){
			$urltrack = 'http://www.trackoncouriers.com/';
		}
		else if ($trackurl == 'SFEXPRESS'){
			$urltrack = 'https://www.sf-express.com/cn/en/dynamic_function/waybill/#search/bill-number/'.$trackno;
		}
		else if ($trackurl == 'WELIVERY'){
			$urltrack = 'https://welivery.cl/tracking?wid='.$trackno;
		}
		else if ($trackurl == 'VASPEXPERSSO'){
			$urltrack = 'https://www.vaspexpresso.pt/geral?search='.$trackno; 
		}
		else if ($trackurl == 'YUNEXPRESS'){
			$urltrack = 'https://www.yuntrack.com/parcelTracking?id='.$trackno; 
		}
		else if ($trackurl == 'SUNYOU'){
			$urltrack = 'https://www.sypost.net/search?orderNo='.$trackno; 
		}
		else if ($trackurl == '4PX'){
			$urltrack = 'https://track.4px.com/#/result/0/'.$trackno; 
		}
		else if ($trackurl == 'DPD'){
			$urltrack = 'https://www.dpdgroup.com/nl/mydpd/my-parcels/track?lang=en&parcelNumber='.$trackno; 
		}
		else if ($trackurl == 'EQUICKCH'){
			$urltrack = 'http://www.equick.cn/index.php?m=content&c=index&a=lists&catid=25&code='.$trackno; 
		}
		else if ($trackurl == 'BUYLOGIC'){
			$urltrack = 'https://www.aftership.com/track/buylogic/'.$trackno; 
		}
		else if ($trackurl == 'JCEX'){
			$urltrack = 'https://www.jcex.com/html/web/guijichaxun/index.html?nums='.$trackno; 
		}
		else if ($trackurl == 'BLUECARE'){
			$urltrack = 'https://www.bluecare.express/Tracking?trackingReference='.$trackno; 
		}
		else if ($trackurl == 'XPRESSBEE'){
			$urltrack = 'https://www.xpressbees.com/track?isawb=Yes&trackid='.$trackno; 
		}
		else if ($trackurl == 'SDAITALIA'){
			$urltrack = 'https://www.sda.it/wps/portal/Servizi_online/dettaglio-spedizione?locale=it&tracing.letteraVettura='.$trackno; 
		}
		else if ($trackurl == 'OSMWORLD'){
			$urltrack = 'https://www.osmworldwide.com/tracking/?trackingNumbers='.$trackno; 
		}
		else if ($trackurl == 'EVRI'){
			$urltrack = 'https://www.evri.com/track/parcel/'.$trackno.'/details'; 
		}
		else if ($trackurl == 'SPANISHSEUR'){
			$urltrack = 'https://www.seur.com/livetracking/?segOnlineIdentificador='.$trackno.'&segOnlineIdioma=es'; 
		}
		else if ($trackurl == 'CHITCHATS'){
			$urltrack = 'https://chitchats.com/tracking/'.$trackno; 
		}
		else if ($trackurl == 'INDIAPOST'){
			$urltrack = 'https://www.aftership.com/track/india-post/'.$trackno; 
		}
		
		return $urltrack;
	}
}
?>