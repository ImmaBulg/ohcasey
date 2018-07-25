<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>OHCASEY: Оплата заказа #{{$order->order_id}}</title>
    <style type="text/css">
        /* /\/\/\/\/\/\/\/\/ CLIENT-SPECIFIC STYLES /\/\/\/\/\/\/\/\/ */
        #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
        body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
        img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

        /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
        body{margin:0; padding:0;}
        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
        table{border-collapse:collapse !important;}
        body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100%;}

        /* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */

        /* ========== Page Styles ========== */

        #bodyCell {
            min-width: 860px!important;
            width: 860px!important;
        }
        #templateContainer {}

        /**
        * @tab Page
        * @sect background style
        * @tip Set the background color and top border for your email. You may want to choose colors that match your company's branding.
        * @theme page
        */
        body, #bodyTable{
            /*@editable*/ background-color:white;
            min-width: 860px!important;
            width: 860px!important;
        }

        span, td, a {
            font-family: 'HelveticaNeueCyr', Arial, Verdana, Helvetica, sans-serif;
        }

        /**
        * @tab Page
        * @sect background style
        * @tip Set the background color and top border for your email. You may want to choose colors that match your company's branding.
        * @theme page
        */
        #bodyCell{
            /*@editable*/
        }

        /**
        * @tab Page
        * @sect email border
        * @tip Set the border for your email.
        */
        #templateContainer{
            /*@editable*/
        }

        /**
        * @tab Page
        * @sect heading 1
        * @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
        * @style heading 1
        */
        h1{
            /*@editable*/ color:#202020 !important;
            display:block;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:26px;
            /*@editable*/ font-style:normal;
            /*@editable*/ font-weight:bold;
            /*@editable*/ line-height:100%;
            /*@editable*/ letter-spacing:normal;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Page
        * @sect heading 2
        * @tip Set the styling for all second-level headings in your emails.
        * @style heading 2
        */
        h2{
            /*@editable*/ color:#404040 !important;
            display:block;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:20px;
            /*@editable*/ font-style:normal;
            /*@editable*/ font-weight:bold;
            /*@editable*/ line-height:100%;
            /*@editable*/ letter-spacing:normal;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Page
        * @sect heading 3
        * @tip Set the styling for all third-level headings in your emails.
        * @style heading 3
        */
        h3{
            /*@editable*/ color:#606060 !important;
            display:block;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:16px;
            /*@editable*/ font-style:italic;
            /*@editable*/ font-weight:normal;
            /*@editable*/ line-height:100%;
            /*@editable*/ letter-spacing:normal;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Page
        * @sect heading 4
        * @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
        * @style heading 4
        */
        h4{
            /*@editable*/ color:#808080 !important;
            display:block;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:14px;
            /*@editable*/ font-style:italic;
            /*@editable*/ font-weight:normal;
            /*@editable*/ line-height:100%;
            /*@editable*/ letter-spacing:normal;
            margin-top:0;
            margin-right:0;
            margin-bottom:10px;
            margin-left:0;
            /*@editable*/ text-align:left;
        }

        /* ========== Header Styles ========== */

        /**
        * @tab Header
        * @sect preheader style
        * @tip Set the background color and bottom border for your email's preheader area.
        * @theme header
        */
        #templatePreheader{
            /*@editable*/ background-color:#F4F4F4;
            /*@editable*/
        }

        /**
        * @tab Header
        * @sect preheader text
        * @tip Set the styling for your email's preheader text. Choose a size and color that is easy to read.
        */
        .preheaderContent{
            /*@editable*/ color:#808080;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:10px;
            /*@editable*/ line-height:125%;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Header
        * @sect preheader link
        * @tip Set the styling for your email's preheader links. Choose a color that helps them stand out from your text.
        */
        .preheaderContent a:link, .preheaderContent a:visited, /* Yahoo! Mail Override */ .preheaderContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#606060;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        /**
        * @tab Header
        * @sect header style
        * @tip Set the background color and borders for your email's header area.
        * @theme header
        */


        /**
        * @tab Header
        * @sect header text
        * @tip Set the styling for your email's header text. Choose a size and color that is easy to read.
        */
        .headerContent{
            /*@editable*/ color:#505050;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:20px;
            /*@editable*/ font-weight:bold;
            /*@editable*/ line-height:100%;
            /*@editable*/ padding-top:0;
            /*@editable*/ padding-right:0;
            /*@editable*/ padding-bottom:0;
            /*@editable*/ padding-left:0;
            /*@editable*/ text-align:left;
            /*@editable*/ vertical-align:middle;
        }

        /**
        * @tab Header
        * @sect header link
        * @tip Set the styling for your email's header links. Choose a color that helps them stand out from your text.
        */
        .headerContent a:link, .headerContent a:visited, /* Yahoo! Mail Override */ .headerContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#EB4102;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        #headerImage {
            height:auto;
            max-width:600px;
        }

        /* ========== Body Styles ========== */

        /**
        * @tab Body
        * @sect body style
        * @tip Set the background color and borders for your email's body area.
        */
        #templateBody{
            /*@editable*/ background-color:#F4F4F4;
            /*@editable*/ border-top:1px solid #FFFFFF;
            /*@editable*/ border-bottom:1px solid #CCCCCC;
        }

        /**
        * @tab Body
        * @sect body text
        * @tip Set the styling for your email's main content text. Choose a size and color that is easy to read.
        * @theme main
        */
        .bodyContent{
            /*@editable*/ color:#505050;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:16px;
            /*@editable*/ line-height:150%;
            padding-top:20px;
            padding-right:20px;
            padding-bottom:20px;
            padding-left:20px;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Body
        * @sect body link
        * @tip Set the styling for your email's main content links. Choose a color that helps them stand out from your text.
        */
        .bodyContent a:link, .bodyContent a:visited, /* Yahoo! Mail Override */ .bodyContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#EB4102;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        .bodyContent img{
            display:inline;
            height:auto;
            max-width:560px;
        }

        /* ========== Column Styles ========== */
        .templateColumnContainer{width:200px;}

        /**
        * @tab Columns
        * @sect chalf_tableolumn style
        * @tip Set the background color and borders for your email's column area.
        */
        #templateColumns{
            /*@editable*/ background-color:#F4F4F4;
            /*@editable*/ border-top:1px solid #FFFFFF;
            /*@editable*/ border-bottom:1px solid #CCCCCC;
        }

        /**
        * @tab Columns
        * @sect left column text
        * @tip Set the styling for your email's left column content text. Choose a size and color that is easy to read.
        */
        .leftColumnContent{
            /*@editable*/ color:#505050;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:14px;
            /*@editable*/ line-height:150%;
            padding-top:0;
            padding-right:20px;
            padding-bottom:20px;
            padding-left:20px;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Columns
        * @sect left column link
        * @tip Set the styling for your email's left column content links. Choose a color that helps them stand out from your text.
        */
        .leftColumnContent a:link, .leftColumnContent a:visited, /* Yahoo! Mail Override */ .leftColumnContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#EB4102;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        center {
            min-width: 860px!important;
            width: 860px!important;
        }

        /**
        * @tab Columns
        * @sect center column text
        * @tip Set the styling for your email's center column content text. Choose a size and color that is easy to read.
        */
        .centerColumnContent{
            /*@editable*/ color:#505050;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:14px;
            /*@editable*/ line-height:150%;
            padding-top:0;
            padding-right:20px;
            padding-bottom:20px;
            padding-left:20px;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Columns
        * @sect center column link
        * @tip Set the styling for your email's center column content links. Choose a color that helps them stand out from your text.
        */
        .centerColumnContent a:link, .centerColumnContent a:visited, /* Yahoo! Mail Override */ .centerColumnContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#EB4102;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        /**
        * @tab Columns
        * @sect right column text
        * @tip Set the styling for your email's right column content text. Choose a size and color that is easy to read.
        */
        .rightColumnContent{
            /*@editable*/ color:#505050;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:14px;
            /*@editable*/ line-height:150%;
            padding-top:0;
            padding-right:20px;
            padding-bottom:20px;
            padding-left:20px;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Columns
        * @sect right column link
        * @tip Set the styling for your email's right column content links. Choose a color that helps them stand out from your text.
        */
        .rightColumnContent a:link, .rightColumnContent a:visited, /* Yahoo! Mail Override */ .rightColumnContent a .yshortcuts /* Yahoo! Mail Override */{
            /*@editable*/ color:#EB4102;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        .leftColumnContent img, .rightColumnContent img{
            display:inline;
            height:auto;
            max-width:260px;
        }

        /* ========== Footer Styles ========== */

        /**
        * @tab Footer
        * @sect footer style
        * @tip Set the background color and borders for your email's footer area.
        * @theme footer
        */
        #templateFooter{
            /*@editable*/ background-color:#F4F4F4;
            /*@editable*/ border-top:1px solid #FFFFFF;
        }

        /**
        * @tab Footer
        * @sect footer text
        * @tip Set the styling for your email's footer text. Choose a size and color that is easy to read.
        * @theme footer
        */
        .footerContent{
            /*@editable*/ color:#808080;
            /*@editable*/ font-family:Helvetica;
            /*@editable*/ font-size:10px;
            /*@editable*/ line-height:150%;
            padding-top:20px;
            padding-right:20px;
            padding-bottom:20px;
            padding-left:20px;
            /*@editable*/ text-align:left;
        }

        /**
        * @tab Footer
        * @sect footer link
        * @tip Set the styling for your email's footer links. Choose a color that helps them stand out from your text.
        */
        .footerContent a:link, .footerContent a:visited, /* Yahoo! Mail Override */ .footerContent a .yshortcuts, .footerContent a span /* Yahoo! Mail Override */{
            /*@editable*/ color:#606060;
            /*@editable*/ font-weight:normal;
            /*@editable*/ text-decoration:underline;
        }

        /* /\/\/\/\/\/\/\/\/ MOBILE STYLES /\/\/\/\/\/\/\/\/ */

        @media only screen and (max-width: 480px){
            /* /\/\/\/\/\/\/ CLIENT-SPECIFIC MOBILE STYLES /\/\/\/\/\/\/ */
            body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:none !important;} /* Prevent Webkit platforms from changing default text sizes */
            body{width:100% !important; min-width:100% !important;} /* Prevent iOS Mail from adding padding to the body */

            /* /\/\/\/\/\/\/ MOBILE RESET STYLES /\/\/\/\/\/\/ */
            #bodyCell{padding:10px !important;}

            /* /\/\/\/\/\/\/ MOBILE TEMPLATE STYLES /\/\/\/\/\/\/ */

            /* ======== Page Styles ======== */

            /**
            * @tab Mobile Styles
                * @sect template width
                * @tip Make the template fluid for portrait or landscape view adaptability. If a fluid layout doesn't work for you, set the width to 300px instead.
                */
            #templateContainer{

                /*@editable*/ width:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect heading 1
                * @tip Make the first-level headings larger in size for better readability on small screens.
                */
            h1{
                /*@editable*/ font-size:24px !important;
                /*@editable*/ line-height:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect heading 2
                * @tip Make the second-level headings larger in size for better readability on small screens.
                */
            h2{
                /*@editable*/ font-size:20px !important;
                /*@editable*/ line-height:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect heading 3
                * @tip Make the third-level headings larger in size for better readability on small screens.
                */
            h3{
                /*@editable*/ font-size:18px !important;
                /*@editable*/ line-height:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect heading 4
                * @tip Make the fourth-level headings larger in size for better readability on small screens.
                */
            h4{
                /*@editable*/ font-size:16px !important;
                /*@editable*/ line-height:100% !important;
            }

            /* ======== Header Styles ======== */

            #templatePreheader{display:none !important;} /* Hide the template preheader to save space */

            /**
            * @tab Mobile Styles
                * @sect header image
                * @tip Make the main header image fluid for portrait or landscape view adaptability, and set the image's original width as the max-width. If a fluid setting doesn't work, set the image width to half its original size instead.
                */
            #headerImage{
                height:auto !important;
                /*@editable*/ max-width:600px !important;
                /*@editable*/ width:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect header text
                * @tip Make the header content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
            .headerContent{
                /*@editable*/ font-size:20px !important;
                /*@editable*/ line-height:125% !important;
            }

            /* ======== Body Styles ======== */

            /**
            * @tab Mobile Styles
                * @sect body text
                * @tip Make the body content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
            .bodyContent{
                /*@editable*/ font-size:18px !important;
                /*@editable*/ line-height:125% !important;
            }

            /* ======== Column Styles ======== */

            .templateColumnContainer{display:block !important; width:100% !important;}

            /**
            * @tab Mobile Styles
                * @sect column image
                * @tip Make the column image fluid for portrait or landscape view adaptability, and set the image's original width as the max-width. If a fluid setting doesn't work, set the image width to half its original size instead.
                */
            .columnImage{
                height:auto !important;
                /*@editable*/ max-width:480px !important;
                /*@editable*/ width:100% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect left column text
                * @tip Make the left column content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
            .leftColumnContent{
                /*@editable*/ font-size:16px !important;
                /*@editable*/ line-height:125% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect center column text
                * @tip Make the center column content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
            .centerColumnContent{
                /*@editable*/ font-size:16px !important;
                /*@editable*/ line-height:125% !important;
            }

            /**
            * @tab Mobile Styles
                * @sect right column text
                * @tip Make the right column content text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
            .rightColumnContent{
                /*@editable*/ font-size:16px !important;
                /*@editable*/ line-height:125% !important;
            }

            /* ======== Footer Styles ======== */

            /**
            * @tab Mobile Styles
                * @sect footer text
                * @tip Make the body content text larger in size for better readability on small screens.
                */
            .footerContent{
                /*@editable*/ font-size:14px !important;
                /*@editable*/ line-height:115% !important;
            }

            .footerContent a{display:block !important;} /* Place footer social and utility links on their own lines, for easier access */

            .admin_table {
                padding: 5px;
            }
            .admin_table  caption {
                font-size: 20px;
                padding-bottom: 5px;
            }

            .half_table td{
                width: 50%;
            }
        }
    </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
<center style="min-width: 100%; width: 100%;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable"
           style="min-width: 860px!important; width: 860px!important;">
        <tr>
            <td align="center" valign="top" id="bodyCell" style="min-width: 860px!important; width: 860px!important;">
                <!-- BEGIN TEMPLATE // -->
                <table border="0" cellpadding="0" cellspacing="0" id="templateContainer"
                       style="min-width: 860px!important; width: 860px!important;">
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN PREHEADER // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templatePreheader"
                                   style="
                                        width: 766px!important;
                                        min-width: 766px!important;
                                        background:#7491b9;
                                        height: 65px;
                                        ">
                                <tr>
                                    <td style="width: 130px; vertical-align: top;
                                                padding-top: 12px;
                                                height: 61px;">
                                        <a href="{{ url('/') }}" title="Наш cайт"
                                           style="letter-spacing: normal; display: block; height:30px; width:110px; ; vertical-align: center; text-align: left; margin-left: 35px;">
                                            <img src="{{ url('img/logo_white.png') }}">
                                        </a>
                                    </td>
                                    <td style="width: 145px;">
                                        <a href="https://instagram.com/_ohcasey_" title="Наш инстаграм"
                                           style="letter-spacing: normal; display: block; height:30px; width:110px; ; vertical-align: center; text-align: right; margin-right: 10px;">
                                            <img src="{{ url('img/instagram.png') }}" style="margin-top: 5px;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <!-- // END PREHEADER -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- BEGIN PREHEADER // -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody" width="765px" style="
                                            width: 765px!important;
                                            min-width: 765px!important;
                                            background:#E8E8E8;
                                            margin-bottom: -20px;
                                        ">
                                <tr>
                                    <td style="padding: 30px 60px;">
                                        <span style="    text-align: center;
                                                    width: 100%;
                                                    display: block;
                                                    font-size: 22px;
                                                    color: #7491b9;">
                                            <span style=" font-weight: normal;
                                                    padding-right: 5px;">{{ (new DateTime($order->order_ts))->setTimezone(new DateTimeZone('Europe/Moscow'))->format('Y-m-d') }}</span>
                                            <span style="font-weight: bold;
                                                    padding-left: 5px;">{{ (new DateTime($order->order_ts))->setTimezone(new DateTimeZone('Europe/Moscow'))->format('H:i T') }}</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0px 60px;  padding-bottom: 20px;">
                                        <span style="
                                            color:  black;
                                            font-size: 21px;
                                            font-weight: bold;
                                            text-align: left;
                                            letter-spacing: 1px;
                                            ">Здравствуйте, {{ $order->client_name }}!</span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 10px;
                                        padding-bottom: 10px;
                                        font-weight: 400;
                                        text-align: left;">Проверьте, пожалуйста, данные, которые Вы указали при оформлении заказа:</span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 10px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Номер заказа: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->order_id }}</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">ФИО: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->client_name }}</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">E-mail: <a style="color:  #405e88!important; text-decoration: none!important;
                                        font-size: 16px;">{{ $order->client_email }}</a></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Телефон: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->client_phone }}</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Страна: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->country->country_name_ru }}</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Доставка: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->delivery->delivery_caption }}</span></span>
                                        @if($order->delivery_type == 'pickpoint' || $order->delivery_type == 'courier')
                                            <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Город: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->deliveryCdek->city->city_name }}</span></span>
                                            @if($order->delivery_type == 'pickpoint')
                                                <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Пункт выдачи: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->deliveryCdek->cdek_pvz.' || '.$order->deliveryCdek->cdek_pvz_name }}</span></span>
                                                <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Адрес пункта: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->deliveryCdek->cdek_pvz_address }}</span></span>
                                                <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Режим работы: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->deliveryCdek->cdek_pvz_worktime }}</span></span>
                                            @endif
                                        @endif
                                        @if(!empty($order->delivery_address))
                                            <span style="display: block; color:  #000000; font-size: 14px; padding-top: 3px; padding-bottom: 3px; font-weight: 400; text-align: left;">Адрес: <span style="color:  #405e88; font-size: 16px;">{{ $order->delivery_address }}</span></span>
                                        @endif
                                        @if(!empty($order->delivery_date))
                                            <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Дата доставки: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->delivery_date }}</span></span>
                                        @endif
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Коментарий: <span style="color:  #405e88; font-size: 16px;">{{ $order->delivery_comment or '-' }}</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 20px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Cумма: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->order_amount + $order->getSpecialItemsSum()}} р.</span></span>
                                        <span style="display: block;
                                        color:  #000000;
                                        font-size: 14px;
                                        padding-top: 3px;
                                        padding-bottom: 3px;
                                        font-weight: 400;
                                        text-align: left;">Доставка: <span style="color:  #405e88;
                                        font-size: 16px;">{{ $order->delivery_amount }} р.</span></span>
                                        @if($order->discount_amount)
                                            <span style="display: block;
                                            color:  #000000;
                                            font-size: 14px;
                                            padding-top: 3px;
                                            padding-bottom: 3px;
                                            font-weight: 400;
                                            text-align: left;">Скидка: <span style="color:  #405e88;
                                            font-size: 16px;">{{ -$order->discount_amount }} р.</span></span>
                                        @endif
                                        <span style="display: block;
                                            color:  #000000;
                                            font-size: 14px;
                                            padding-top: 3px;
                                            padding-bottom: 3px;
                                            font-weight: 400;
                                            text-align: left;">Итого: <span style="color:  #405e88;
                                            font-size: 16px;">{{ $order->getTotalSum() }} р. </span></span>
                                        @if ($order->getTotalSum() != $payment->amount)
                                            <span style="display: block;
                                            color:  #000000;
                                            font-size: 14px;
                                            padding-top: 3px;
                                            padding-bottom: 3px;
                                            font-weight: 400;
                                            text-align: left;">К оплате: <span style="color:  #405e88;
                                            font-size: 16px;">{{ $payment->amount }} р. </span></span>
                                        @endif

                                        <a href="{{route('payment.do_pay', ['paymentHash' => $payment->hash])}}"
                                           style="text-decoration: none; letter-spacing: normal; display: block; background: #2F6EA3;  height:30px; padding-top: 12px; text-transform: uppercase; color: white; width:200px; margin-left: 220px; margin-top: 38px; text-align: center;">
                                            Оплатить {{ $payment->amount }} р.
                                        </a>

                                        @foreach($order->cart->cartSetCase as $item)
                                            <table style="width: 100%; margin-top: 20px;">
                                                <tr>
                                                    <td style="background-repeat: no-repeat; background-size: contain; background-position: top right; width: 34%; text-align: right;">
                                                        <img src="{{ url('orders', [$order->order_id, $order->order_hash, 'item_'.$item->cart_set_id.'.png']) }}" height="240px" width="auto" style="height: 250px; width: auto">
                                                    </td>
                                                    <td style="width: 2%;"></td>
                                                    <td style="width: 49%;">
                                                        <table>
                                                            <tr>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center; color: #669AC4; font-size: 16px; font-weight: 300; text-align: center;">
                                                                    <span style ="display: block; padding: 5px 0px; width:100%;">{{ $item->device->device_caption }}</span>
                                                                    <img alt="" src="{{ url('storage/device', [$item->device_name, 'icon.png']) }}">
                                                                </td>
                                                                <td style="width: 3px;"></td>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center;">
                                                                    <span style="color:  #405e88; font-size: 18px; font-weight: 300; text-align: center;">{{ $item->casey->case_caption }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr style="height: 5px;"></tr>
                                                            <tr>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center;">
                                                                    <span style="text-transform: uppercase;color: #405e88; font-size: 16px; font-weight: 300; text-align: center;">
                                                                        #ОБЪЁМНАЯ ПЕЧАТЬ
                                                                    </span>
                                                                    <br>
                                                                    <span style=" color:  #669AC4; font-size: 16px; display: block; padding-top: 4px;">уникальная технология</span>
                                                                </td>
                                                                <td style="width: 3px;"></td>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; color: #669AC4; font-size: 18px; text-align: center;">
                                                                    <span style="font-size: 18px;">Цена:</span><br>
                                                                    <span style="display: block;padding-top: 4px;">{{ $item->item_cost }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>

                                            </table>
                                        @endforeach

                                        @foreach($order->cart->cartSetProducts as $item)
                                            <table style="width: 100%; margin-top: 20px;">
                                                <tr>
                                                    <td style="background-repeat: no-repeat; background-size: contain; background-position: top right; width: 34%; text-align: right;">
                                                        <img src="{{ url($item->offer->product->mainPhoto())  }}" height="240px" width="auto" style="height: 250px; width: auto">
                                                    </td>
                                                    <td style="width: 2%;"></td>
                                                    <td style="width: 49%;">
                                                        <table>
                                                            <tr>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center; color: #669AC4; font-size: 16px; font-weight: 300; text-align: center;">
                                                                    <span style ="display: block; padding: 5px 0px; width:100%;">{{ $item->offer->product->name }}</span>
                                                                </td>
                                                                <td style="width: 3px;"></td>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center;">
																	@if (is_array($item->offer->option_values))
                                                                    <span style="color:  #405e88; font-size: 18px; font-weight: 300; text-align: center;">{{ $item->offer->option_values->implode(', ') }}</span>
																	@endif
																</td>
                                                            </tr>
                                                            <tr style="height: 5px;"></tr>
                                                            <tr>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; text-align: center;">

                                                                </td>
                                                                <td style="width: 3px;"></td>
                                                                <td style="width: 122px; height: 122px; background-color: #f7f7f7; color: #669AC4; font-size: 18px; text-align: center;">
                                                                    <span style="font-size: 18px;">Цена:</span><br>
                                                                    <span style="display: block;padding-top: 4px;">{{ $item->item_cost }}</span>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>

                                            </table>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                            <!-- // END PREHEADER -->
                        </td>
                    </tr>
                </table>
                <!-- // END TEMPLATE -->
            </td>
        </tr>
        <tr>
            <td style="background-repeat: no-repeat; display: block; margin-top:-20px; background: url({{ url('img/mail_background.png') }}); vertical-align: top; padding: 60px 110px ;" height="411px" width="640px">
                <a href="https://instagram.com/_ohcasey_" title="Наш инстаграм" style="letter-spacing: normal; display: block; background: #7491b9; padding: 10px 15px; padding-top: 20px; height:30px; width:110px; margin-left: 245px; margin-top: 38px;" width="130px" height="60px">
                    <img src="{{ url('img/instagram.png') }}">
                </a>
                <span style="
                                color: #ffffff;
                                font-size: 14px;
                                font-weight: bold;
                                text-align: center;
                                width: 100%;
                                display: block;
                                margin-top: 160px;
                            ">Главный офис</span>
                <span style="color:  #ffffff;
                            font-size: 14px;/* Приближение из-за подстановки шрифтов */
                            text-align: center;
                             display: block;
                                 margin-top: 13px;
                            width: 100%;">Тел: <a href="tel:79653969785" title="Наш телефон" style="color:white!important; text-decoration: none;">+7(965)396-97-85</a></span>
                <span style="color: white!important;
                            font-size: 14px;/* Приближение из-за подстановки шрифтов */
                            text-align: center;
                             display: block;
                                 margin-top: 13px;
                            width: 100%;">e-mail: <a href="mailto:info@ohcasey.ru" title="Наш email" style="color: #ffffff!important; text-decoration: none;">info@ohcasey.ru</a></span>
                <span style="color:  #ffffff;
                            font-size: 14px;/* Приближение из-за подстановки шрифтов */
                            text-align: center;
                             display: block;
                                 margin-top: 13px;
                            width: 100%;">Адрес: Москва, ул таганская 24 стр 1</span>
            </td>
        </tr>

    </table>
</center>
</body>
</html>
