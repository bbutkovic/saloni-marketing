<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>Privacy Policy</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/salon-website.css') }}
    {{ HTML::style('css/alt-page.css') }}

</head>

<body id="privacyPolicy">
<nav class="header-navigation navbar navbar-default navbar-fixed-top">
    <div class="header-wrap">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="header-logo">
                <a href="{{ URL::to('/').'/'.$salon->unique_url }}">
                    <img src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" alt="{{ $salon->business_name }}">
                </a>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-main nav-header">
                <li class="active">
                <li><a class="page-scroll" href="{{ URL::to('/').'/'.$salon->unique_url }}">{{ trans('salon.home') }}</a></li>
                <li><a class="page-scroll" href="{{ route('salonBlog', $salon->unique_url) }}">{{ trans('salon.news') }}</a></li>
                </li>
                @if(count($salon->locations) > 1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('salon.locations') }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach($salon->locations as $location)
                                <li class="dropdown-link"><a href="{{ URL::to('/').'/'.$salon->unique_url.'/'.$location->unique_url }}">{{ $location->location_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a class="page-scroll" href="{{ URL::to('/').'/'.$salon->unique_url.'/'.$salon->locations[0]['unique_url'] }}">{{ trans('salon.about_salon') }}</a></li>
                @endif
                <li>
                    <a href="{{ route('clientBooking', $salon->unique_url) }}" id="bookNowBtn" style="background-color: {{ $salon->website_content->book_btn_bg }}; color: {{ $salon->website_content->book_btn_color }}">{{ $salon->website_content->book_btn_text }}</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right nav-header">
                @if($salon->website_content->facebook_link != null)
                    <li><a href="{{ $salon->website_content->facebook_link }}"><i class="fa fa-facebook-f"></i></a></li>
                @endif
                @if($salon->website_content->twitter_link != null)
                    <li><a href="{{ $salon->website_content->twitter_link }}"><i class="fa fa-twitter"></i></a></li>
                @endif
                @if($salon->website_content->instagram_link != null)
                    <li><a href="{{ $salon->website_content->instagram_link }}"><i class="fa fa-instagram"></i></a></li>
                @endif
                @if($salon->website_content->pinterest_link != null)
                    <li><a href="{{ $salon->website_content->pinterest_link }}"><i class="fa fa-pinterest-p"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
    </div>
</nav>
<h1 class="policy-header text-center">Privacy Policy</h1>
<div class="privacy-policy-wrapper text-left">
    <h2><strong>Privacy policy</strong></h2>
    <p>Data protection is of a particularly high priority for the management of this website SALONI MARKETING.
        The use of these Internet pages is possible without any indication of personal data. However, if a data
        subject wants to use special enterprise services via our website, processing of personal data is necessary.
        If the processing of personal data is necessary and there is no statutory basis for such processing, we generally obtain consent from the data subject.</p>
    <p>In this Terms and Conditions You will find out how we collect and store data, and how Your data is used.</p>

    <h3 class="mb-2"><strong>1. Definitions</strong></h3>
    <p>The data protection declaration of the SALONI MARKETING is based on the terms conducted by the European legislator
        for the adoption of the General Data Protection Regulation. Our data protection declaration should be legible and
        understandable for the general public, as well as our customers and business partners. To ensure this, we would like to first explain used terminology.
        In this data protection declaration, we use, inter alia, the following terms:</p>
    <h4 class="mb-2">1.1. Personal Data</h4>
    <p>Personal data means any information relating to an identified or identifiable natural person (“data subject”). An identifiable
        natural person is one who can be identified, directly or indirectly, in particular by reference to an identifier such as a name,
        an identification number, location data, an online identifier or to one or more factors specific to the natural, physiological,
        genetic, mental, economic, cultural or social identity of that natural person.</p>
    <h4 class="mb-2">1.2. Processing</h4>
    <p>Processing is any operation or set of operations which is performed on personal data or on sets of personal data, whether or not by
        automated means, such as collection, recording, organisation, structuring, storage, adaptation or alteration, retrieval, consultation,
        use, disclosure by transmission, dissemination or otherwise making available, alignment or combination, restriction, erasure or destruction.</p>
    <h4 class="mb-2">1.3. Restriction of Processing</h4>
    <p>Restriction of processing is the marking of stored personal data with the aim of limiting their processing in the future.</p>
    <h4 class="mb-2">1.4. Profiling</h4>
    <p>Profiling means any form of automated processing of personal data consisting of the use of personal data to evaluate certain personal aspects relating
        to a natural person, in particular to analyse or predict aspects concerning that natural person’s performance at work, economic situation, health,
        personal preferences, interests, reliability, behaviour, location or movements.</p>
    <h4 class="mb-2">1.5. Pseudonymisation</h4>
    <p>Pseudonymisation is the processing of personal data in such a manner that the personal data can no longer be attributed to a specific data subject without
        the use of additional information, provided that such additional information is kept separately and is subject to technical and organisational measures to
        ensure that the personal data are not attributed to an identified or identifiable natural person.</p>
    <h4 class="mb-2">1.6. Controller or Controllers Responsible for the Processing</h4>
    <p>Controller (or controllers) responsible for the processing is natural or legal person, public authority, agency or other body which, alone or jointly with others,
        determines the purposes and means of the processing of personal data; where the purposes and means of such processing are determined by Union or Member State law,
        the controller or the specific criteria for its nomination may be provided for by Union or Member State law.</p>
    <h4 class="mb-2">1.7. Processor</h4>
    <p>Processor is a natural or legal person, public authority, agency or other body which processes personal data on behalf of the controller.</p>
    <h4 class="mb-2">1.8. Consent</h4>
    <p>Consent of the data subject is any freely given, specific, informed and unambiguous indication of the data subject’s wishes by which he or she, by a statement or by a
        clear affirmative action, signifies agreement to the processing of personal data relating to him or her.</p>
    <h3 class="mb-2"><strong>2. Cookies</strong></h3>
    <p>The web site of the controller uses cookies. Cookies are text files that are stored on a computer system via Internet browser.</p>
    <p>Through the use of cookies, controller can provide the users of this website with more user-friendly services that would not be possible without the cookie setting.</p>
    <p>The data subject may, at any time, prevent the setting of cookies through our web site by means of a corresponding setting of the Internet browser used, and may thus permanently
        deny the setting of cookies. Furthermore, already set cookies may be deleted at any time via an Internet browser or other software programs. This is possible in all popular
        Internet browsers. If the data subject deactivates the setting of cookies in the Internet browser used, not all functions of our web site may be entirely usable.</p>
    <h3 class="mb-2"><strong>3. Collection of General Data and Information</strong></h3>
    <p>The web site of the controller collects a series of general data and information when a data subject or automated system calls up the web site. This general data and information
        are stored in the server log files. Collected data may be the browser type and version, the operating system used by the accessing system, the web site from which an accessing
        system reaches our web site (so-called referrers), the sub-websites, the date and time of access to the web site, an Internet protocol address (IP address), the Internet service
        provider of the accessing system, and any other similar data and information that may be used in the event of attacks on our information technology systems.</p>
    <p>When using these general data and information, the controller does not draw any conclusions about the data subject. Rather, this information is needed to:</p>
    <ul class="mb-3">
        <li>deliver the content of our web site correctly,</li>
        <li>optimise the content of our web site as well as its advertisement,</li>
        <li>ensure the long-term viability of our information technology systems and website technology,</li>
        <li>and to provide law enforcement authorities with the information necessary for criminal prosecution in case of a cyber-attack.</li>
    </ul>
    <p>Therefore, the controller analyses anonymously collected data and information statistically, with the aim of increasing the data protection and data security of our enterprise.</p>
    <h3 class="mb-2"><strong>4. Registration</strong></h3>
    <p>Posjetitelj ima mogućnost registracije na ovoj web stranici. </p>
    <p>The data subject can register on the controller’s web site with the indication of personal data. Which personal data are transmitted to the controller is determined by the respective
        input mask used for the registration. The personal data entered by the data subject are collected and stored exclusively for internal use by the controller, and for his own purposes.
        The controller may request transfer to one or more processors (e.g. a parcel service) that also uses personal data for an internal purpose which is attributable to the controller.</p>
    <p>The registration of the data subject, with the voluntary indication of personal data, is intended to enable the controller to offer the data subject contents or services that may only
        be offered to registered users due to the nature of the matter in question. Registered persons are free to change the personal data specified during the registration at any time,
        or to have them completely deleted from the data stock of the controller.</p>
    <p>The data controller shall, at any time, provide information upon request to each data subject as to what personal data are stored about the data subject. In addition, the data controller
        shall correct or erase personal data at the request or indication of the data subject, insofar as there are no statutory storage obligations. The entirety of the controller’s employees
        is available to the data subject in this respect as contact persons.</p>
    <h3 class="mb-2"><strong>5. Contact Possibility via the Web Site</strong></h3>
    <p>The website of the controller contains information that enables a quick electronic contact to our enterprise, as well as direct communication with us, which also includes a general address
        of the so-called electronic mail (e-mail address). If a data subject contacts the controller by e-mail or via a contact form, the personal data transmitted by the data subject are
        automatically stored. Such personal data transmitted on a voluntary basis by a data subject to the data controller are stored for the purpose of processing or contacting the data
        subject. There is no transfer of this personal data to third parties.</p>
    <h3 class="mb-2"><strong>6. Deletion and Blocking of Personal Data</strong></h3>
    <p>The data controller shall process and store the personal data of the data subject only for the period necessary to achieve the purpose of storage, or as far as this is granted by the
        European legislator or other legislators in laws or regulations to which the controller is subject to.</p>
    <p>If the storage purpose is not applicable, or if a storage period prescribed by the European legislator or another competent legislator expires, the personal data are routinely blocked or erased in accordance with legal requirements.</p>
    <h3 class="mb-2"><strong>7. Rights of the Data Subject</strong></h3>
    <p>Regarding the processing of data, the data subject has the rights listed in the following several chapters.</p>
    <h4 class="mb-2">7.1. Right of Confirmation</h4>
    <p>Each data subject shall have the right granted by the European legislator to obtain from the controller the
        confirmation as to whether or not personal data concerning him or her are being processed.
        If a data subject wishes to avail himself/herself of this right of confirmation, he or she may, at any time, contact any employee of the controller.</p>
    <h4 class="mb-2">7.2. Right of Access</h4>
    <p>Each data subject shall have the right granted by the European legislator to obtain from the controller free information about his or her personal data stored at any time and a copy of this information. Furthermore, the European directives and regulations grant the data subject access to the following information:</p>
    <ul class="mb-3">
        <li>the purposes of the processing;</li>
        <li>the categories of personal data concerned;</li>
        <li>the recipients or categories of recipients to whom the personal data have been or will be disclosed, in particular recipients in third countries or international organisations;</li>
        <li>where possible, the envisaged period for which the personal data will be stored, or, if not possible, the criteria used to determine that period;li>
        <li>the existence of the right to request from the controller rectification or erasure of personal data, or restriction of processing of personal data concerning the data subject, or to object to such processing;</li>
        <li>the existence of the right to lodge a complaint with a supervisory authority;</li>
        <li>where the personal data are not collected from the data subject, any available information as to their source;</li>
        <li>the existence of automated decision-making, including profiling, referred to in Article 22 1 and 4 of the GDPR and, at least in those cases, meaningful information about the logic involved, as well as the significance and envisaged consequences of such processing for the data subject.</li>
    </ul>
    <p>Furthermore, the data subject shall have a right to obtain information as to whether personal data are transferred to a third country or to an international organisation. Where this is the case, the data subject shall have the right to be informed of the appropriate safeguards relating to the transfer.</p>
    <p>Ako ispitanik želi iskoristiti ovo pravo na pristup, u svakom trenutku može kontaktirati voditelja obrade
        osobnih podataka.</p>
    <h4 class="mb-2">7.3. Right to Rectify</h4>
    <p>Each data subject shall have the right granted by the European legislator to obtain from the controller without undue delay the rectification of inaccurate personal data concerning him or her. Taking into account the purposes of the processing, the data subject shall have the right to have incomplete personal data completed, including by means of providing a supplementary statement.</p>
    <p>If a data subject wishes to exercise this right to rectification, he or she may, at any time, contact any employee of the controller.</p>
    <h4 class="mb-2">7.4. Right to Deletion</h4>
    <p>Each data subject shall have the right granted by the European legislator to obtain from the controller the erasure of personal data concerning him or her without undue delay, and the controller shall have the obligation to erase personal data without undue delay where one of the following grounds applies, as long as the processing is not necessary:</p>
    <ul class="mb-3">
        <li>The personal data are no longer necessary in relation to the purposes for which they were collected or otherwise processed.</li>
        <li>The data subject withdraws consent to which the processing is based according to point (a) of Article 6(1) of the GDPR, or point (a) of Article 9(2) of the GDPR, and where there is no other legal ground for the processing.</li>
        <li>The data subject objects to the processing pursuant to Article 21(1) of the GDPR and there are no overriding legitimate grounds for the processing, or the data subject objects to the processing pursuant to Article 21(2) of the GDPR.</li>
        <li>The personal data have been unlawfully processed.</li>
        <li>The personal data must be erased for compliance with a legal obligation in Union or Member State law to which the controller is subject.</li>
        <li>The personal data have been collected in relation to the offer of information society services referred to in Article 8(1) of the GDPR.</li>
    </ul>
    <p>If one of the aforementioned reasons applies, and a data subject wishes to request the erasure of personal data stored by the controller, he or she may, at any time, contact any employee of the controller. An employee of the controller shall promptly ensure that the erasure request is complied with immediately.</p>
    <p>Where the controller has made personal data public and is obliged pursuant to Article 17(1) to erase the personal data, the controller, taking account of available technology and the cost of implementation, shall take reasonable steps, including technical measures, to inform other controllers processing the personal data that the data subject has requested erasure by such controllers of any links to, or copy or replication of, those personal data, as far as processing is not required. Employees of the controller will arrange the necessary measures in individual cases.</p>
    <h4 class="mb-2">7.5. Right to Restrict the Processing</h4>
    <p>Each data subject shall have the right granted by the European legislator to obtain from the controller restriction of processing where one of the following applies:</p>
    <ul class="mb-3">
        <li>The accuracy of the personal data is contested by the data subject, for a period enabling the controller to verify the accuracy of the personal data.</li>
        <li>The processing is unlawful and the data subject opposes the erasure of the personal data and requests instead the restriction of their use instead.</li>
        <li>The controller no longer needs the personal data for the purposes of the processing, but they are required by the data subject for the establishment, exercise or defence of legal claims.</li>
        <li>The data subject has objected to processing pursuant to Article 21(1) of the GDPR pending the verification whether the legitimate grounds of the controller override those of the data subject.</li>
    </ul>
    <p>If one of the aforementioned conditions is met, and a data subject wishes to request the restriction of the processing of personal data stored by the controller, he or she may at any time contact any employee of the controller. The employee of the controller will arrange the restriction of the processing.</p>
    <h4 class="mb-2">7.6. Right to Data Portability</h4>
    <p>Each data subject shall have the right granted by the European legislator, to receive the personal data concerning him or her, which was provided to a controller, in a structured, commonly used and machine-readable format. He or she shall have the right to transmit those data to another controller without hindrance from the controller to which the personal data have been provided, as long as the processing is based on consent pursuant to point (a) of Article 6(1) of the GDPR or point (a) of Article 9(2) of the GDPR, or on a contract pursuant to point (b) of Article 6(1) of the GDPR, and the processing is carried out by automated means, as long as the processing is not necessary for the performance of a task carried out in the public interest or in the exercise of official authority vested in the controller.</p>
    <p>Furthermore, in exercising his or her right to data portability pursuant to Article 20(1) of the GDPR, the data subject shall have the right to have personal data transmitted directly from one controller to another, where technically feasible and when doing so does not adversely affect the rights and freedoms of others.</p>
    <p>In order to assert the right to data portability, the data subject may at any time contact any employee of the controller.</p>
    <h4 class="mb-2">7.7. Right to Object</h4>
    <p>Each data subject shall have the right granted by the European legislator to object, on grounds relating to his or her particular situation, at any time, to processing of personal data concerning him or her, which is based on point (e) or (f) of Article 6(1) of the GDPR. This also applies to profiling based on these provisions.</p>
    <p>The controller shall no longer process the personal data in the event of the objection, unless we can demonstrate compelling legitimate grounds for the processing which override the interests, rights and freedoms of the data subject, or for the establishment, exercise or defence of legal claims.</p>
    <p>If the controller processes personal data for direct marketing purposes, the data subject shall have the right to object at any time to processing of personal data concerning him or her for such marketing. This applies to profiling to the extent that it is related to such direct marketing. If the data subject objects to the controller to the processing for direct marketing purposes, the controller will no longer process the personal data for these purposes.</p>
    <p>In addition, the data subject has the right, on grounds relating to his or her particular situation, to object to processing of personal data concerning him or her by the controller for scientific or historical research purposes, or for statistical purposes pursuant to Article 89(1) of the GDPR, unless the processing is necessary for the performance of a task carried out for reasons of public interest.</p>
    <p>In order to exercise the right to object, the data subject may contact any employee of the controller. In addition, the data subject is free in the context of the use of information society services, and notwithstanding Directive, to use his or her right to object by automated means using technical specifications.</p>
    <h4 class="mb-2">7.8. Automated Individual Decision-making Including Profiling</h4>
    <p>Each data subject shall have the right granted by the European legislator not to be subject to a decision based solely on automated processing, including profiling, which produces legal effects concerning him or her, or similarly significantly affects him or her, as long as the decision is not is necessary for entering into, or the performance of, a contract between the data subject and a data controller, or is not authorised by Union or Member State law to which the controller is subject and which also lays down suitable measures to safeguard the data subject’s rights and freedoms and legitimate interests, or is not based on the data subject’s explicit consent.</p>
    <p>If the decision is necessary for entering into, or the performance of, a contract between the data subject and a data controller, or it is based on the data subject’s explicit consent, the controller shall implement suitable measures to safeguard the data subject’s rights and freedoms and legitimate interests, at least the right to obtain human intervention on the part of the controller, to express his or her point of view and contest the decision.</p>
    <p>If the data subject wishes to exercise the rights concerning automated individual decision-making, he or she may, at any time, contact any employee of the controller.</p>
    <h4 class="mb-2">7.9. Right to Withdraw Data Protection Consent</h4>
    <p>Each data subject shall have the right granted by the European legislator to withdraw his or her consent to processing of his or her personal data at any time.</p>
    <p>If the data subject wishes to exercise the right to withdraw the consent, he or she may, at any time, contact any employee of the controller.</p>
    <h3 class="mb-2"><strong>8. Data Protection and Procedures</strong></h3>
    <p>The data controller shall collect and process the personal data of applicants for the purpose of the processing of the application procedure. The processing may also be carried out electronically. This is the case, in particular, if an applicant submits corresponding application documents by e-mail or by means of a web form on the website to the controller. If the data controller concludes an employment contract with an applicant, the submitted data will be stored for the purpose of processing the employment relationship in compliance with legal requirements. If no employment contract is concluded with the applicant by the controller, the application documents shall be automatically erased two months after notification of the refusal decision, provided that no other legitimate interests of the controller are opposed to the erasure. Other legitimate interest in this relation is, e.g. a burden of proof in a procedure under the General Equal Treatment Act.</p>
    <h3 class="mb-2"><strong>9. Data Protection Provisions for Social Networks</strong></h3>
    <p>A social network is a place for social meetings on the Internet, an online community, which usually allows users to communicate with each other and interact in a virtual space. A social network may serve as a platform for the exchange of opinions and experiences, or enable the Internet community to provide personal or business-related information.</p>
    <h4 class="mb-2">9.1. Facebook</h4>
    <p>On this website, the controller has integrated components of the enterprise Facebook social network. Facebook allows its users to create private profiles, upload photos, and network through friend requests.</p>
    <p>The operating company of Facebook is Facebook, Inc., 1 Hacker Way, Menlo Park, CA 94025, United States. If a person lives outside of the United States or Canada, the controller is the Facebook Ireland Ltd., 4 Grand Canal Square, Grand Canal Harbour, Dublin 2, Ireland.</p>
    <p>With each call-up to one of the individual pages of this Internet website, which is operated by the controller and into which a Facebook component (Facebook plug-ins) was integrated, the web browser on the information technology system of the data subject is automatically prompted to download display of the corresponding Facebook component from Facebook through the Facebook component. An overview of all the Facebook Plug-ins may be accessed under https://developers.facebook.com/docs/plugins/. During the course of this technical procedure, Facebook is made aware of what specific sub-site of our website was visited by the data subject.</p>
    <p>If the data subject is logged in at the same time on Facebook, Facebook detects with every call-up to our website by the data subject—and for the entire duration of their stay on our Internet site—which specific sub-site of our Internet page was visited by the data subject. This information is collected through the Facebook component and associated with the respective Facebook account of the data subject. If the data subject clicks on one of the Facebook buttons integrated into our website, e.g. the “Like” button, or if the data subject submits a comment, then Facebook matches this information with the personal Facebook user account of the data subject and stores the personal data.</p>
    <p>Facebook always receives, through the Facebook component, information about a visit to our website by the data subject, whenever the data subject is logged in at the same time on Facebook during the time of the call-up to our website. This occurs regardless of whether the data subject clicks on the Facebook component or not. If such a transmission of information to Facebook is not desirable for the data subject, then he or she may prevent this by logging off from their Facebook account before a call-up to our website is made.</p>
    <p>The data protection guideline published by Facebook, which is available at https://facebook.com/about/privacy/, provides information about the collection, processing and use of personal data by Facebook. In addition, it is explained there what setting options Facebook offers to protect the privacy of the data subject. In addition, different configuration options are made available to allow the elimination of data transmission to Facebook. These applications may be used by the data subject to eliminate a data transmission to Facebook.</p>
    <h3 class="mb-2"><strong>10. Data Protection Provisions for Analytics Systems</strong></h3>
    <p>Analytical systems are online services that allow tracking and analysis of traffic on web sites. The web site owner provides specific information about the user's movement on the web site, which are the most popular subpages, which links are most often opened by users, etc. All that information allows the web site owner to adjust web site content and functionality to the user's preferences.</p>
    <p>This web app does not use any analytic systems.</p>
    <h3 class="mb-2"><strong>11. Legal Basis for the Processing</strong></h3>
    <p>Article 6, Paragraph 1, Sub-paragraph A of GDPR serves as the legal basis for processing operations for which we obtain consent for a specific processing purpose.</p>
    <p>If the processing of personal data is necessary for the performance of a contract to which the data subject is party, as is the case, for example, when processing operations are necessary for the supply of goods or to provide any other service, the processing is based on Article 6, Paragraph 1, Sub-paragraph B of GDPR. The same applies to such processing operations which are necessary for carrying out pre-contractual measures, for example in the case of inquiries concerning our products or services. </p>
    <p>If our company is subject to a legal obligation by which processing of personal data is required, such as for the fulfilment of tax obligations, the processing is based on Article 6, Paragraph 1, Sub-paragraph C of GDPR. </p>
    <p>In rare cases, the processing of personal data may be necessary to protect the vital interests of the data subject or of another natural person. This would be the case, for example, if a visitor were injured in our company and his name, age, health insurance data or other vital information would have to be passed on to a doctor, hospital or other third party. Then the processing would be based on Article 6, Paragraph 1, Sub-paragraph D of GDPR.</p>
    <p>Finally, processing operations could be based on Article 6, Paragraph 1, Sub-paragraph F of GDPR. This legal basis is used for processing operations which are not covered by any of the abovementioned legal grounds, if processing is necessary for the purposes of the legitimate interests pursued by our company or by a third party, except where such interests are overridden by the interests or fundamental rights and freedoms of the data subject which require protection of personal data. Such processing operations are particularly permissible because they have been specifically mentioned by the European legislator. He considered that a legitimate interest could be assumed if the data subject is a client of the controller.</p>
    <h3 class="mb-2"><strong>12. The Legitimate Interests Pursued By the Controller or By a Third Party</strong></h3>
    <p>Where the processing of personal data is based on Article 6, Paragraph 1, Sub-paragraph F of GDPR our legitimate interest is to carry out our business in favour of the well-being of all our employees and the shareholders.</p>
    <h3 class="mb-2"><strong>13. Personal Data Storing Period</strong></h3>
    <p>The criteria used to determine the period of storage of personal data is the respective statutory retention period. After expiration of that period, the corresponding data is routinely deleted, as long as it is no longer necessary for the fulfilment of the contract or the initiation of a contract.</p>
    <h3 class="mb-2"><strong>14. Provision of Personal Data, Data Subject’s Obligations and Consequences of Failure to Provide Data</strong></h3>
    <p>We clarify that the provision of personal data is partly required by law (e.g. tax regulations) or can also result from contractual provisions (e.g. information on the contractual partner). Sometimes it may be necessary to conclude a contract that the data subject provides us with personal data, which must subsequently be processed by us. The data subject is, for example, obliged to provide us with personal data when our company signs a contract with him or her. The non-provision of the personal data would have the consequence that the contract with the data subject could not be concluded.
        Before personal data is provided by the data subject, the data subject must contact any employee. The employee clarifies to the data subject whether the provision of the personal data is required by law or contract or is necessary for the conclusion of the contract, whether there is an obligation to provide the personal data and the consequences of non-provision of the personal data.</p>
    <h3 class="mb-2"><strong>15. Existence of Automated Decision-Making</strong></h3>
    <p>As a responsible company, we do not use automatic decision-making or profiling.</p>
    <h3 class="mb-2"><strong>16. Safety Statement</strong></h3>
    <p>The safety of data on this web site is ensured by using the Secure Socket Layer (SSL) security protocol with 128-bit data encryption. Data exchange is thus protected from unauthorized access. Furthermore, any user data store on our website is encrypted with 256-bit encryption.</p>
    <h3 class="mb-2"><strong>17. Accuracy, Completeness and Timeliness of Information</strong></h3>
    <p>We are not responsible if the information available on this website is inaccurate or incomplete. The materials from this website are used at their own risk. You agree that it is your responsibility to keep track of all changes made to material and information found on this website.</p>
    <h3 class="mb-2"><strong>18. Intellectual Property Rights</strong></h3>
    <p>All copyrights and other intellectual property rights contained in all texts, pictures and other materials on this website are property of the controller or are included with the permission of the respective owner.</p>
    <p>It is allowed to browse the site, reproduce outputs through printing, save to the hard disk, all in your private non-commercial purpose. All materials from this website may be published if you retain copyright and other property rights. No reproduction of any part of this site may be marketed or distributed for commercial purposes and may not be modified or incorporated into any other business, publication or website.</p>
    <p>Trademarks, logos, icons, slogans and service marks displayed on this website belong to the controller. All content of this website should not be construed as a license to use any trademark or logo displayed on this site. Your use and/or misuse of any content of these websites are strictly forbidden. If you break this ban the controller will enforce its intellectual property right fully, including a criminal lawsuit for serious offenses.</p>
    <h3 class="mb-2"><strong>19. Privacy Protection</strong></h3>
    <p>Privacy protection describes how the controller handles your personal details which are received during usage of web site. Under personal details it is considered your identification data: name and last name, e-mail address, home address, OIB and phone number, in other words data that is not publicly accessible and which are known to the controller due to your usage of web site. The controller obligates that the company will use your personal data only for identification purposes during the controller’s web site usage, therefore you will be able to use all the options given by the web site. The controller will keep your personal details in secret and will not distribute, publish, give to the third parties or in any other way make them public to any other third party before your previous consent.</p>
    <h4 class="mb-2">19.1. Consent</h4>
    <p>With my signature I am giving my consent to SALONI MARKETING as a collector of data to:</p>
    <ul class="mb-3">
        <li>process the information I sent by using the contact options listed on the website</li>
        <li>send me promotion information</li>
        <li>statistically analyse my web site visits</li>
    </ul>
    <p>The controller may collect and process my personal data (first name, last name, address, e-mail, and phone number).</p>
    <p>With this consent I confirm that the controller informed me about my rights, which are:</p>
    <ul class="mb-3">
        <li>the right to receive information about my personal information stored by the controller;</li>
        <li>the right to request corrections, deletions or restrictions on the processing of my personal data;</li>
        <li>the right to object to the processing of data for reasons related to some of my justified interests, public interest or profiling, unless there is no need for justified reasons beyond my interests, rights and freedoms or if the data are not processed for the purposes of determining, achieving or defending legal requirements. In the case of processing for immediate promotional purposes, I always have the authority to oppose the same;
        </li>
        <li>the right to data transferring;</li>
        <li>the right to file a complaint with the body responsible for data protection;</li>
        <li>the ability at any time to withdraw my consent to collecting, processing and the use of my personal data.
        </li>
    </ul>
    <p>If you wish to exercise your rights, please submit a request to the controller by mail or by e-mail.</p>
    <ul class="mb-3">
        <li>address... or by</li>
        <li>
            email <a href="...">...</a>.</li>
    </ul>
    <p>This consent is valid until the revocation or termination of the processing due to the fulfilment of the purpose for which it was given. Personal data will be deleted and their processing will stop.</p>
    <h3 class="mb-2"><strong>20. Online Communication</strong></h3>
    <p>By visiting the controller’s web site you communicate electronically online. Therefore you agree to all consents, notifications, announcements and other contents which are delivered to you online and through this way they satisfy legal framework as they were written.</p>
    <h3 class="mb-2"><strong>21. Changes of Policy</strong></h3>
    <p>The controller keeps all rights to change and update this Policy, without prior announcement.</p>

</div>

</body>

</html>
