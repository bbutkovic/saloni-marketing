<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>Pravila Privatnosti</title>

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
<h1 class="policy-header text-center">Pravila Privatnosti</h1>
<div class="privacy-policy-wrapper text-left">
    <h2>Pravila Privatnosti</h2>
    <p>Zaštita osobnih podataka na ovoj web stranici ima veliku važnost. Za korištenje nekih usluga na ovoj web
        stranici potrebna je obrada osobnih podataka. SALONI MARKETING obvezuje se na zaštitu vaših osobnih podataka,
        a ukoliko je obrada podataka nužna, korisnika će se uvijek pitati pristanak.</p>
    <p>U nastavku Pravila Privatnosti objašnjeno je koje podatke prikupljamo i kako ih koristimo, te koje metode zaštite
        su poduzete u svrhu zaštite Vaših osobnih podataka.</p>

    <h3 class="mb-2">1. Definicije</h3>
    <p>Pravila privatnosti ove web stranice temelje se na pojmovima koje je koristilo
        Zakonodavstvo EU pri izradi Opće uredbe o zaštiti osobnih podataka. Pravila privatnosti čitka su i
        razumljiva široj javnosti, kao i klijentima i poslovnim partnerima. Kako bismo to osigurali, prvo ćemo
        objasniti korištenu terminologiju.</p>
    <p>U ovim Pravilima privatnosti, među ostalima, koristimo i sljedeće pojmove:</p>
    <h4 class="mb-2">1.1. Osobni podaci</h4>
    <p>Osobni podaci su bilo koji podaci koji se odnose na bilo koju identificiranu fizičku osobu.
        Identificirana fizička osoba ona je koju se može identificirati, direktno ili indirektno, po imenu i
        prezimenu, identifikacijskom broju, podacima o lokaciji, online identifikatorima kao što su fizički,
        psihički, genetski, mentalni, ekonomski, kulturni ili društveni identitet fizičke osobe.</p>
    <h4 class="mb-2">1.2. Obrada</h4>
    <p>Obrada je bilo koji postupak ili skup postupaka primjenjivih na osobne podatke, bilo da se radi o
        automatiziranim procesima kao što su prikupljanje, snimanje, organizacija, strukturiranje, spremanje,
        prilagodba ili izmjena, vađenje, pregled, korištenje, otkrivanje putem prijenosa, širenje ili stavljanje
        na raspolaganje, poravnavanje, ograničavanje, brisanje ili uništenje.</p>
    <h4 class="mb-2">1.3. Ograničenje obrade</h4>
    <p>Ograničenje obrade označavanje je spremljenih osobnih podataka s ciljem da se ograniči njihova
        obrada u budućnosti.</p>
    <h4 class="mb-2">1.4. Profiliranje</h4>
    <p>Profiliranje je bilo koji način automatizirane obrade osobnih podataka koji se sastoji od korištenja
        osobnih podataka kako bi se utvrdile osobne preference fizičke osobe, točnije kako bi se analizirali i
        previdjeli aspekti uspješnosti na poslu, ekonomske situacije, zdravlja, osobnih preferencija, interesa,
        pouzdanosti, ponašanja, lociranja i kretanja svake pojedine fizičke osobe.</p>
    <h4 class="mb-2">1.5. Pseudonimizacija</h4>
    <p>Pseudonimizacija je obrada osobnih podataka na način da se ispitaniku više ne mogu dodijeliti
        osobine bez korištenja dodatnih podataka koji se prikupljaju na način da su odvojeni i na njima se
        mogu primijeniti mjere tehničke i organizacijske prirode koje osiguravaju da se podaci ne mogu
        primijeniti na bilo koju fizičku osobu čiji je identitet utvrđen ili se može utvrditi.</p>
    <h4 class="mb-2">1.6. Voditelj obrade osobnih podataka ili osoba zadužena kao voditelj obrade osobnih
        podataka</h4>
    <p>Voditelj obrade osobnih podataka ili osoba zadužena kao voditelj obrade osobnih podataka svaka je
        fizička ili pravna osoba, javna ustanova, agencija ili drugo tijelo koje samo ili u suradnji s drugima
        određuje svrhu i način obrade osobnih podataka; na mjestima gdje je svrha i način obrade osobnih
        podataka uređen zakonodavstvom države članice EU, kriterij za izbor voditelja obrade bit će pod
        ingerencijom zakonodavstva države članice EU.</p>
    <h4 class="mb-2">1.7. Izvršitelj obrade osobnih podataka</h4>
    <p>Izvršitelj obrade osobnih podataka fizička je ili pravna osoba, javna ustanova, agencija ili drugo tijelo
        koje vrši obradu osobnih podataka u ime voditelja obrade osobnih podataka.</p>
    <h4 class="mb-2">1.8. Privola</h4>
    <p>Privola ispitanika svaka je točno određena, informirana i nedvosmislena indikacija ispitanikove želje
        po kojoj on ili ona putem izjave ili jasne potvrdne radnje izražava pristanak na obradu osobnih
        podataka koji su vezani za njega ili nju.</p>
    <h3 class="mb-2">2. Kolačići</h3>
    <p>Ova web stranica koristi kolačiće. Kolačići su tekstualne datoteke koje preglednik sprema na
        računalni sustav.</p>
    <p>Korištenjem kolačića ova web stranica posjetiteljima pruža pristupačnije usluge koje ne bi
        bile moguće bez korištenja kolačića.</p>
    <p>Informacije i ponude web stranice mogu se putem kolačića optimizirati tako da je na prvom mjestu korisnik i
        njegovo korisničko iskustvo. Svrha kolačića je u tome da korisnik lakše koristi web stranicu. Npr., korisnik
        koji koristi kolačiće ne mora pri svakom pristupanju web stranici unositi pristupne podatke jer taj postupak
        preuzima web stranica koja smješta kolačić na računalni sustav.</p>
    <p>Posjetitelj može u bilo koje doba spriječiti smještanje kolačića putem editiranja postavki preglednika kojeg
        koristi, i tako može trajno onemogućiti smještanje kolačića. Nadalje, već postavljeni kolačići mogu se
        ukloniti u bilo koje doba putem bilo kojeg preglednika. No, ako ispitanik deaktivira postavljanje kolačića u
        svom pregledniku, neće moći koristiti sve funkcije web stranice.</p>
    <h3 class="mb-2">3. Prikupljanje općih i osobnih podataka, i informacija</h3>
    <p>Ova web stranica prikuplja opće podatke i informacije kad ispitanik ili automatizirani sustav
        pristupi web stranici. Ti opći podaci i informacije potom se spremaju na server u vidu log datoteka.
        Prikuplja se tip preglednika i njegova verzija, operacijski sustav, web stranica s koje se pristupa ovoj web
        stranici (tzv. preporučitelji), pod-stranica, datum i vrijeme pristupanja web stranici, IP adresa, pružatelj
        internetske usluge i drugi slični podaci i informacije koje se mogu koristiti u slučaju napada na
        informacijski sustav.</p>
    <p>Pri korištenju tih općih podataka i informacija ne izvlačimo zaključke o ispitaniku. Suprotno tome, ove
        informacije su potrebne kako bi se:</p>
    <ul class="mb-3">
        <li>sadržaj web stranice ispravno dostavio,</li>
        <li>optimizirao sadržaj web stranice i njen marketing,</li>
        <li>osigurala dugotrajna sposobnost sustava i web stranice,</li>
        <li>pružila potrebna pomoć državnim tijelima u slučaju kaznenog progona zbog cyber-napada.</li>
    </ul>
    <p>Osobni podaci prikupljaju se u svrhu registracije i izvršavanja procesa online bookinga. Ti podaci potrebni su u
        svrhu identifikacije korisnika.</p>
    <h3 class="mb-2">4. Registracija</h3>
    <p>Posjetitelj ima mogućnost registracije na ovoj web stranici. </p>
    <p>Registriranjem na web stranicu voditelja, pohranjuju se i datum i vrijeme IP adrese koju dodjeljuje
        pružatelj internetskih usluga i koju koristi ispitanik. Čuvanje ovih podataka odvija se u pozadini jer je to
        jedini način da se spriječi zlouporaba naših usluga i da, ako je potrebno, omogućimo istragu počinjenih
        prekršaja. Pohranjivanje tih podataka potrebno je za osiguranje voditelja. Ti se podaci ne prenose trećim
        stranama, osim ako postoji zakonska obveza prijenosa podataka ili ako prijenos služi svrsi kaznenog
        progona.</p>
    <p>Registracija ispitanika, uz dobrovoljnu naznaku osobnih podataka, neophodna je omogućiti voditelju da
        ponudi sadržaje ili usluge koje se mogu ponuditi samo registriranim korisnicima zbog prirode predmeta.
        Registrirane osobe mogu u bilo kojem trenutku promijeniti osobne podatke navedene tijekom registracije ili
        ih potpuno izbrisati iz zbirke osobnih podataka voditelja.</p>
    <p>Voditelj obrade osobnih podataka mora u svakom trenutku dati informacije po zahtjevu svakom ispitaniku o
        tome koji su osobni podaci pohranjeni o ispitaniku. Nadalje, voditelj zbirke osobnih podataka će ispraviti
        ili izbrisati osobne podatke na zahtjev ili naznaku nositelja podataka (ispitanika), pod uvjetom da ne
        postoje zakonske obveze pohrane. Svi zaposlenici voditelja dostupni su ispitaniku u tom pogledu kao kontakt
        osobe.</p>
    <h3 class="mb-2">5. Mogućnost kontakta putem web stranice</h3>
    <p>Web stranica sadrži informacije koje omogućuju brz kontakt putem elektronskih medija kao
        i direktnu komunikaciju koja uključuje i adresu e-pošte. Ako ispitanik kontaktira voditelja obrade osobnih
        podataka putem e-pošte ili kontaktnog obrasca preneseni osobni podaci su automatski spremljeni. Osobni
        podaci preneseni dobrovoljno od strane ispitanika prema voditelju obrade osobnih podataka automatski se
        pohranjuju u svrhu obrade ili daljnje komunikacije s ispitanikom. Ne postoji prijenos ove vrste osobnih
        podataka prema trećoj strani.</p>
    <h3 class="mb-2">6. Brisanje ili blokiranje osobnih podataka</h3>
    <p>Voditelj obrade osobnih podataka obrađivati će i držati osobne podatke ispitanika jedino u vremenu koje je
        potrebno kako bi se ostvarili ciljevi držanja osobnih podataka ili do roka koji dopušta Zakonodavstvo EU ili
        drugi zakonodavci pod čijom je nadležnosti voditelj obrade osobnih podataka.</p>
    <p>Ako se razlog spremanja osobnih podataka ne može ispuniti ili period čuvanja određen od strane
        Zakonodavstva EU ili drugih nadležnih zakonodavaca istekne, osobni podaci ispitanika rutinski će se
        blokirati ili izbrisati u skladu sa zakonskim preduvjetima.</p>
    <h3 class="mb-2">7. Prava korisnik</h3>
    <p>U vezi s obradom podataka, korisnik ima prava navedena u sljedećih nekoliko poglavlja.</p>
    <h4 class="mb-2">7.1. Pravo na potvrdu osobnih podataka</h4>
    <p>Svaki korisnik ima pravo garantirano od strane Zakonodavstva EU da od voditelja obrade osobnih podataka
        dobije potvrdu koriste li se ili obrađuju njegovi ili njeni osobni podaci. Ako ispitanik želi iskoristiti
        ovo pravo na potvrdu, on ili ona u svakom trenutku može kontaktirati voditelja obrade osobnih podataka.</p>
    <h4 class="mb-2">7.2. Pravo na pristup osobnim podacima</h4>
    <p>Svaki korisnik ima pravo garantirano od strane Zakonodavstva EU da u svakom trenutku od voditelja obrade
        osobnih podataka dobije besplatnu informaciju o svojim osobnim podacima koji su pohranjeni, kao i kopiju
        traženih osobnih podataka. Nadalje, Europske odredbe i direktive omogućuju ispitaniku pristup sljedećim
        informacijama:</p>
    <ul class="mb-3">
        <li>svrha obrade osobnih podataka;</li>
        <li>vrsta traženih osobnih podataka;</li>
        <li>primatelj ili vrsta primatelja s kojima su podijeljeni osobni podaci, posebno primatelji iz trećih
            zemalja ili međunarodnih organizacija;
        </li>
        <li>gdje je moguće, predviđeni period čuvanja osobnih podataka ili u slučaju nemogućnosti kriterij koji
            određuje taj period;
        </li>
        <li>postojanje prava ispitanika da od voditelja obrade osobnih podataka zatraži ispravak ili brisanje
            osobnih podataka, ograničenje obrade osobnih podataka ispitanika ili pravo ispitanika na prigovor protiv
            obrade osobnih podataka;
        </li>
        <li>postojanje prava podnošenja žalbe nadzornom tijelu;</li>
        <li>ako osobni podaci nisu prikupljeni direktno od ispitanika, dostupne informacije o izvoru osobnih
            podataka;
        </li>
        <li>postojanje automatiziranog procesa donošenja odluka Opće Uredbe o zaštiti osobnih podataka i u
            navedenom slučaju dostupne informacije o logici automatizma, kao i važnost i predviđene posljedice po
            ispitanika.
        </li>
    </ul>
    <p>Nadalje, ispitanik ima pravo na informaciju ako se njegovi ili njeni osobni podaci prenose na treće zemlje
        ili međunarodne organizacije. U tom slučaju ispitanik ima pravo na informaciju o sigurnosnim mjerama
        provedenim u prijenosu podataka.</p>
    <p>Ako ispitanik želi iskoristiti ovo pravo na pristup, u svakom trenutku može kontaktirati voditelja obrade
        osobnih podataka.</p>
    <h4 class="mb-2">7.3. Pravo na ispravak osobnih podataka</h4>
    <p>Svaki ispitanik ima pravo garantirano od strane Zakonodavstva EU da u svakom trenutku od voditelja obrade
        osobnih podataka dobije ispravak netočnih osobnih podataka. Imajući u vidu svrhu obrade osobnih podataka,
        ispitanik ima pravo da mu/joj se nepotpuni osobni podaci upotpune, uz ostalo i putem upotpunjujuće
        izjave.</p>
    <p>Ako ispitanik želi iskoristiti ovo pravo na ispravak, u svakom trenutku može kontaktirati voditelja obrade
        osobnih podataka.</p>
    <h4 class="mb-2">7.4. Pravo na brisanje osobnih podataka</h4>
    <p>Svaki ispitanik ima pravo garantirano od strane europskog zakonodavstva da u svakom trenutku od voditelja
        obrade osobnih podataka traži brisanje osobnih podataka vezanih uz ispitanika bez odgode. Voditelj obrade
        osobnih podataka ima obvezu bez odgode obrisati osobne podatke gdje je primjenjiv barem jedan od uvjeta, sve
        dok obrada nije nužna:</p>
    <ul class="mb-3">
        <li>Osobni podaci više nisu potrebni u smislu svrhe za koju su prikupljeni ili obrađeni.</li>
        <li>Ispitanik je povukao privolu za obradu osobnih podataka koja se temelji na člancima i stavkama Opće
            uredbe o zaštiti osobnih podataka i gdje više ne postoji zakonska osnova za obradu podataka.
        </li>
        <li>Ispitanik se protivi obradi podataka prema člancima i stavkama Opće uredbe o zaštiti osobnih podataka,
            a ne postoji zakonska osnova za obradu podataka, ili se ispitanik protivi obradi podataka prema člancima i
            stavkama Opće uredbe o zaštiti osobnih podataka.
        </li>
        <li>Osobni podaci su protuzakonito obrađeni.</li>
        <li>Osobni podaci se moraju izbrisati prema zakonskoj obavezi po Zakonodavstvu EU ili zakonima države
            članice čije je voditelj obrade osobnih podataka državljanin.
        </li>
        <li>Osobni podaci su prikupljeni u svezi s ponudom usluge informacijskog društva prema člancima i stavkama
            Opće uredbe o zaštiti osobnih podataka.
        </li>
    </ul>
    <p>Ukoliko je barem jedan od gore navedenih razloga primjenjiv, a ispitanik zatraži brisanje osobnih podataka
        koje prikuplja web stranica, on ili ona mogu kontaktirati voditelja obrade osobnih
        podataka. Voditelj obrade osobnih podataka će osigurati da se brisanje osobnih podataka izvrši odmah.</p>
    <p>Na mjestima gdje je voditelj obrade osobnih podataka dopustio objavu osobnih podataka, a obavezno je
        brisanje navedenih osobnih podataka, voditelj obrade osobnih podataka će vodeći obzira o tehničkoj
        izvedivosti i troškovima primjene poduzeti razumne korake, uključujući tehničke mjere, kako bi obavijestio
        druge voditelje obrade osobnih podataka da je ispitanik zatražio brisanje svih poveznica, kopija ili replika
        osobnih podataka, sve dok njihova obrada više nije potrebna. Voditelj obrade osobnih podataka na web
        stranici će osigurati provedbu navedenih mjera u svakom pojedinačnom slučaju.</p>
    <h4 class="mb-2">7.5. Pravo na ograničenje obrade osobnih podataka</h4>
    <p>Svaki ispitanik po Zakonodavstvu EU ima zajamčeno pravo da od voditelja obrade osobnih podataka pribavi
        pravo na ograničenje obrade osobnih podataka u slučajevima gdje je sljedeće primjenjivo:</p>
    <ul class="mb-3">
        <li>Točnost osobnih podataka je osporena od strane ispitanika što omogućuje voditelju obrade osobnih
            podataka da provjeri točnost osobnih podataka.
        </li>
        <li>Obrada osobnih podataka je protuzakonita, a ispitanik se protivi brisanju osobnih podataka i umjesto
            toga traži ograničenje uporabe navedenih osobnih podataka.
        </li>
        <li>Voditelju obrade osobnih podataka osobni podaci više nisu potrebni za obradu, međutim ispitaniku su
            potrebni za uspostavu, provedbu ili obranu pravnih zahtjeva.
        </li>
        <li>Ispitanik se usprotivio obradi osobnih podataka prema člancima i stavkama Opće uredbe o zaštiti
            osobnih podataka čekajući provjeru nadjačavaju li zakonski temelji voditelja obrade osobnih podataka one
            ispitanika.
        </li>
    </ul>
    <p>Ukoliko je barem jedan od gore navedenih razloga primjenjiv, a ispitanik zatraži ograničenje obrade osobnih
        podataka koje prikuplja web stranica, on ili ona mogu kontaktirati voditelja obrade
        osobnih podataka. Voditelj obrade osobnih podataka će osigurati ograničenje obrade osobnih podataka.</p>
    <h4 class="mb-2">7.6. Pravo na prenosivost osobnih podataka</h4>
    <p>Svaki ispitanik po Zakonodavstvu EU ima zajamčeno pravo da primi osobne podatke koji se odnose na njega ili
        nju od strane voditelja obrade osobnih podataka, u strukturiranom, većinski korištenom i čitkom formatu.
        Ispitanik ima pravo prenijeti navedene osobne podatke nekom drugom voditelju obrade osobnih podataka od
        trenutnog voditelja obrade osobnih podataka bez ikakvih smetnji, sve dok je obrada osobnih podataka
        temeljena na privoli sukladno Općoj uredbi o zaštiti osobnih podataka ili po ugovoru prema Općoj uredbi o
        zaštiti osobnih podataka, a obrada podataka se vrši automatski, sve dok obrada podataka nije nužna za
        zadatke od javnog interesa ili za obavljanje službene dužnosti voditelja obrade osobnih podataka.</p>
    <p>Nadalje, po postojećem pravu na prenosivost osobnih podataka, ispitanik ima pravo da se njegovi ili njeni
        osobni podaci prenesu direktno između voditelja obrade osobnih podataka gdje je to tehnički izvedivo i gdje
        taj postupak ne ugrožava prava i slobode drugih ispitanika.</p>
    <p>Kako bi iskoristio svoje pravo na prenosivost osobnih podataka, ispitanik u svakom trenutku može
        kontaktirati voditelja obrade osobnih podataka web stranice.</p>
    <h4 class="mb-2">7.7. Pravo na prigovor</h4>
    <p>Svaki ispitanik po Zakonodavstvu EU ima zajamčeno pravo na prigovor temeljen na vlastitoj situaciji, u
        svakom trenutku, na obradu osobnih podataka koji se odnose na ispitanika, a temelji se na Općoj uredbi o
        zaštiti osobnih podataka. Također se može primijeniti na profiliranje temeljeno na ovoj Uredbi.</p>
    <p>Ova web stranica u slučaju prigovora neće nastaviti obradu osobnih podataka, osim u slučaju
        kad postoji ozbiljan zakonski temelj za obradu osobnih podataka, a koji može premostiti interese, prava i
        slobode ispitanika ili za uspostavu, provedbu ili obranu pravnih zahtjeva.</p>
    <p>Ukoliko web stranica vrši obradu osobnih podataka u marketinške svrhe, ispitanik ima
        pravo na prigovor u bilo kojem trenutku protiv obrade osobnih podataka koji se koriste u te svrhe. Ovo se
        primjenjuje i za profiliranje koje je usko povezano za svrhe takvog direktnog marketinga. Ako ispitanik
        izrazi prigovor protiv obrade osobnih podataka u svrhe direktnog marketinga, web stranica
        više neće vršiti obradu osobnih podataka ispitanika za svrhu direktnog marketinga.</p>
    <p>Dodatno, ispitanik ima na osnovu vlastite situacije pravo na prigovor protiv obrade osobnih podataka od
        strane web stranice, a koji se koriste u znanstveno-istraživačke ili statističke svrhe,
        osim ako je obrada podataka potrebna u svrhu javnog interesa.</p>
    <p>Kako bi iskoristio pravo na prigovor, ispitanik u svakom trenutku može kontaktirati voditelja obrade
        osobnih podataka web stranice. Uz to, ispitanik u kontekstu korištenja usluga
        informacijskog društva, usprkos Uredbi, može iskoristiti svoje pravo na prigovor automatiziranim sredstvima
        koristeći se tehničkim specifikacijama.</p>
    <h4 class="mb-2">7.8. Automatizirano donošenje odluka i profiliranje</h4>
    <p>Svaki ispitanik po Zakonodavstvu EU ima zajamčeno pravo da ne bude predmet donošenja odluka koje se temelje
        isključivo na automatiziranoj obradi osobnih podataka, uključujući profiliranje, a koje mogu stvoriti pravne
        ili slične posljedice po njega ili nju, sve dok odluka nije dio dogovora između ispitanika i voditelja
        obrade osobnih podataka ili nije dopuštena po zakonima EU ili pojedine države članice koji donose primjerene
        mjere koji će čuvati prava, slobode i interese ispitanika ili nije temeljena na izričitoj privoli
        ispitanika.</p>
    <p>Ako je odluka potrebna za ugovor između ispitanika i voditelja obrade osobnih podataka ili se temelji na
        izričitoj privoli ispitanika, web stranica će provesti mjere koje će čuvati ispitanikova
        prava, slobode i interese, minimalno pravo na ljudsku intervenciju od strane voditelja osobnih podataka
        kojem će izraziti svoj stav i osporavanje odluke.</p>
    <p>Ako ispitanik želi iskoristiti prava u vezi automatiziranog individualnog donošenja odluka, u svakom
        trenutku može kontaktirati voditelja obrade osobnih podataka web stranice.</p>
    <h4 class="mb-2">7.9. Pravo na povlačenje i ukidanje privole</h4>
    <p>Svaki ispitanik po Zakonodavstvu EU ima zajamčeno pravo da povuče i/ili ukine svoju privolu za obradu
        osobnih podataka u svakom trenutku.</p>
    <p>Ako ispitanik želi iskoristiti pravo da povuče i/ili ukine svoju privolu, u svakom trenutku može
        kontaktirati voditelja obrade osobnih podataka web stranice.</p>
    <h3 class="mb-2">8. Zaštita podataka podnositelja zahtjeva i procesa obrade podnesenih podataka</h3>
    <p>Voditelj obrade osobnih podataka prikuplja i obrađuje osobne podatke podnositelja zahtjeva u svrhu obrade
        zahtjeva. Obrada se također može provesti elektronskim putem. To je slučaj ako podnositelj zahtjeva
        dostavlja odgovarajuće dokumente za zahtjev putem elektronske pošte ili putem web obrasca na web stranici
        voditelja. Ako voditelj sklapa ugovor o radu s podnositeljem zahtjeva, dostavljeni podaci bit će pohranjeni
        u svrhu provedbe radnog odnosa u skladu sa zakonskim zahtjevima. Ako voditelj nije sklopio ugovor o radu s
        podnositeljem prijave, prijave se automatski brišu dva mjeseca nakon obavijesti o odluci odbijanja, pod
        uvjetom da se nikakvi drugi legitimni interesi voditelja ne protive brisanju.</p>
    <h3 class="mb-2">9. Odredbe vezane za društvene mreže</h3>
    <p>Društvena mreža mjesto je društvenog povezivanja na Internetu. To je online zajednica koja obično dopušta
        korisnicima da međusobno komuniciraju u virtualnom prostoru. Društvena mreža služi kao platforma za razmjenu
        mišljenja i iskustava ili kako bi Internet zajednici pružila osobne ili informacije vezane za posao.</p>
    <h4 class="mb-2">9.1. Facebook</h4>
    <p>Na web stranici voditelj obrade osobnih podataka ugradio je komponente društvene mreže
        Facebook. Facebook svojim korisnicima dopušta izradu privatnih profila, prijenos slika i povezivanje putem
        zahtjeva za prijateljstvo.</p>
    <p>Facebook je vlasništvo tvrtke Facebook, Inc., 1 Hacker Way, Menlo Park, CA 94025, SAD. Ako osoba koja se
        koristi Facebook-om živi izvan SAD-a ili Kanade, voditelj obrade osobnih podataka je Facebook Ireland Ltd.,
        4 Grand Canal Square, Grand Canal Harbour, Dublin 2, Irska.</p>
    <p>Otvaranjem bilo koje stranice web stranice kojom upravlja voditelj obrade osobnih
        podataka, a na kojoj je ugrađena Facebook komponenta, ispitanikov preglednik prikazati će odgovarajuću
        Facebook komponentu. Pregled svih Facebook komponenti može se naći na
        <a href="https://developers.facebook.com/docs/plugins/">https://developers.facebook.com/docs/plugins/</a>.
        Za vrijeme ove tehničke procedure Facebook-u je pružena
        informacija o specifičnoj pod-stranici koju je posjetio ispitanik.</p>
    <p>Ako je ispitanik prijavljen na Facebook, u isto vrijeme Facebook može detektirati svaki njegov pregled web
        stranice, a tokom boravka na web stranici koje je specifične pod-stranice posjetio. Ova
        informacija prikuplja se putem Facebook komponente i doznačuje se Facebook računu ispitanika. Ako ispitanik
        klikne na bilo koji Facebook gumb integriran na web stranici npr. „Share“ gumb ili ako
        ispitanik pošalje komentar, tada Facebook doznačuje navedenu informaciju osobnom Facebook računu ispitanika
        i time sprema osobne podatke.</p>
    <p>Svaki put kad je ispitanik prijavljen na Facebook, a u isto vrijeme pristupa web stranici, Facebook će putem komponente
        primiti informaciju o tome. Ovo će se dogoditi bez obzira klikne li ispitanik na Facebook komponentu ili ne. Ukoliko
        ispitanik ne želi prijenos ove vrste informacije, isto može spriječiti tako da se odjavi s Facebook računa prije pristupa
        ovoj web stranici.</p>
    <p>Smjernice o zaštiti osobnih podataka koje je Facebook objavio na <a
                href="https://facebook.com/about/privacy/">https://facebook.com/about/privacy/</a>
        pružaju informacije o prikupljanju, obradi i korištenju osobnih podataka od strane Facebook-a. Također,
        objašnjeno je koje postavke Facebook pruža kako bi se zaštitili osobni podaci ispitanika. Isto tako, na
        raspolaganju su razne konfiguracijske opcije kako bi se onemogućio prijenos osobnih podataka prema
        Facebook-u. Ispitanik može koristiti navedene opcije u svrhu onemogućavanja slanja osobnih podataka.</p>
    <h3 class="mb-2">10. Odredbe vezane za analitičke sustave</h3>
    <p>Analitički sustavi su online usluge koje omogućavaju praćenje i analizu prometa na web stranicama. Vlasniku
        web stranice daju konkretnu informaciju o kretanju korisnika na web stranici, koje su pod-stranice
        najposjećenije, koje linkove korisnici najčešće otvaraju i sl. Sve to vlasniku web stranice omogućava da
        njen sadržaj i funkcionalnost prilagodi preferencijama korisnika.</p>
    <p>Web stranica ne koristi analitičke sustave.</p>
    <h3 class="mb-2">11. Pravna osnova za obradu osobnih podataka</h3>
    <p>Članak 6, Stavak 1, Podstavak A Opću uredbe o zaštiti osobnih podataka pravna je osnova za postupke obrade
        osobnih podataka za koje smo dobili privolu. </p>
    <p>Ako je obrada osobnih podataka nužna za provedbu ugovora kojeg je ispitanik dio, kao npr. kad je obrada
        osobnih podataka nužna za isporuku roba ili usluga, obrada osobnih podataka temelji se na Članku 6, Stavku
        1, Podstavku B Opće uredbe o zaštiti osobnih podataka. Isto se primjenjuje prilikom obrada osobnih podataka
        koje su potrebne kako bi se provele predugovorne mjere, npr. pri upitima vezanim za proizvode ili
        usluge. </p>
    <p>Ako je pravna ili fizička osoba predmet pravne obveze kod koje je obrada osobnih podataka nužna, kao npr.
        ispunjavanje poreznih obveza, obrada osobnih podataka temelji se na Članku 6, Stavku 1, Podstavku C Opće
        uredbe o zaštiti osobnih podataka. </p>
    <p>U rijetkim slučajevima obrada osobnih podataka je neophodna kako bi se zaštitili interesi ispitanika ili
        neke druge fizičke osobe. Npr., u slučaju da se ispitanik ozlijedi prilikom posjeta prostoru vlasnika ove
        web stranice, osobni podaci poput imena, dobi, podataka o zdravstvenom osiguranju ili neka druga vitalna
        informacija će se morati proslijediti liječniku, bolnici ili nekoj trećoj osobi. Takva obrada podataka
        temelji se na Članku 6, Stavku 1, Podstavku D Opće uredbe o zaštiti osobnih podataka. </p>
    <p>Na kraju, obrada osobnih podataka može se temeljiti i na Članku 6, Stavku 1, Podstavku F Opće uredbe o
        zaštiti osobnih podataka. Ova pravna osnova koristi se u postupcima obrade osobnih podataka kada nije
        primjenjiva niti jedna prijašnja pravna osnova, ako je obrada osobnih podataka nužna za legitimne interese
        vlasnika web stranice ili neke treće strane, osim u situacijama gdje navedeni interesi
        ugrožavaju osnovna prava i slobode ispitanika, a koji zahtijevaju zaštitu osobnih podataka. Ovakva obrada
        osobnih podataka je naročito dozvoljena budući da je posebno napomenuta od Zakonodavstva EU. Predviđeno je
        da se moguće pozvati na legitimni interes ako je ispitanik klijent voditelja obrade osobnih podataka.</p>
    <h3 class="mb-2">12. Legitimni interesi voditelja obrade osobnih podataka ili treće strane</h3>
    <p>Na mjestima gdje se obrada osobnih podataka temelji na Članku 6, Stavku 1, Podstavku F Opće uredbe o
        zaštiti osobnih podataka, legitimni interes predstavlja poslovanje u korist dobrobiti zaposlenika i/ili
        dioničara ako navedeni postoje.</p>
    <h3 class="mb-2">13. Razdoblje pohrane osobnih podataka</h3>
    <p>Kriterij pri određivanju razdoblja pohrane osobnih podataka zakonom je određeno maksimalnim razdobljem
        pohrane. Nakon isteka tog razdoblja, osobni podaci su rutinski izbrisani, sve dok više nisu potrebni kako bi
        se ispunio ugovor ili njegovo sklapanje.</p>
    <h3 class="mb-2">14. Pružanje osobnih podataka, obveze ispitanika i posljedice ne pružanja podataka</h3>
    <p>Moramo razjasniti da je pružanje osobnih podataka djelomično propisano zakonom (npr. porezne odredbe) ili
        može biti rezultat ugovorne obveze (npr. informacija o ugovornom partneru). Ponekad je potrebno zaključiti
        ugovor tako da ispitanik pruži osobne podatke koji se potom obrađuju. Ispitanik je, na primjer, nužan
        pružiti osobne podatke kad s vlasnikom web stranice sklapa ugovor. Odbijanje pružanja
        osobnih podataka za posljedicu će imati nemogućnost sklapanja ugovora. Prije nego ispitanik pruži osobne
        podatke morat će kontaktirati vlasnika web stranice. On će mu potom naznačiti je li
        pružanje osobnih podataka zakonski ili ugovorno nužno, odnosno je li nužno za sklapanje ugovora, postoji li
        obveza pružanja osobnih podataka i na kraju posljedice ako odbije pružiti osobne podatke.</p>
    <h3 class="mb-2">15. Postojanje automatizacijskog procesa donošenja odluka</h3>
    <p>Kao odgovoran poslovni partner, web stranica ne koristi automatizirani proces donošenja
        odluka, odnosno profiliranje.</p>
    <h3 class="mb-2">16. Izjava o sigurnosti</h3>
    <p>Sigurnost podataka na ovim web stranicama osigurana je korištenjem sigurnosnog protokola Secure Socket
        Layer (SSL) sa 128-bitnom enkripcijom podataka. Razmjena podataka na taj je način zaštićena od
        neautoriziranog pristupa. Također, osobni podaci svakog korisnika zaštićeni su 256-bitnom enkripcijom podataka.</p>
    <h3 class="mb-2">17. Točnost, potpunost i pravodobnost informacija</h3>
    <p>Nismo odgovorni ukoliko informacije dostupne na ovim stranicama nisu točne ili potpune. Materijali s ovih
        web stranica koriste se na vlastitu odgovornost. Suglasni ste da je Vaša odgovornost pratiti sve promjene
        materijala i informacija koje se nalaze na ovih web stranicama.</p>
    <h3 class="mb-2">18. Prava na intelektualno vlasništvo</h3>
    <p>Sva autorska prava i ostala prava na intelektualno vlasništvo sadržana u svim tekstovima, slikama i ostalim
        materijalima na ovim web stranicama vlasništvo su vlasnika web stranice ili su uključena
        uz dopuštenje odgovarajućeg vlasnika.</p>
    <p>Dopušteno je pregledavanje stranice, reproduciranje izvadaka kroz tiskanje, spremanje na tvrdi disk, sve u
        Vaše privatne nekomercijalne svrhe. Svi materijali s ovih web stranica mogu se objavljivati pod uvjetom da
        sačuvate autorska prava i ostala prava na vlasništvo. Nijedna reprodukcija nijednog dijela ovih web stranica
        ne smije se prodati ili distribuirati u komercijalne svrhe i ne smije biti modificirana ili uklopljena u
        druga poslovanja, publikacije ili web stranice.</p>
    <p>Robni žigovi, logotipi, znakovi, slogani i uslužni žigovi prikazani na ovim web stranicama pripadaju
        . Sav sadržaj ovih web stranica ne treba se tumačiti kao dozvola za korištenje bilo kojeg
        robnog žiga ili logotipa prikazanog na ovim web stranicama. Vaše korištenje i/ili zlouporaba bilo kojih
        sadržaja ovih web stranica strogo je zabranjena. Ako prekršite tu zabranu provesti će
        svoje zakonsko pravo na intelektualno vlasništvo u potpunosti, uključujući i kaznenu tužbu za ozbiljne
        prekršaje.</p>
    <h3 class="mb-2">19. Zaštita privatnosti</h3>
    <p>Zaštita privatnosti opisuje kako postupa s Vašim osobnim podacima koje zaprimi tijekom
        korištenja web stranice. Pod osobnim podacima smatraju se Vaši identifikacijski podaci: ime i prezime,
        e-mail adresa, kućna adresa, OIB i telefonski broj, odnosno podaci koji inače nisu javno dostupni, a za koje
        se sazna tijekom korištenja web stranice. Obvezujemo se da će Vaši osobne podaci biti korišteni samo u
        identifikacijske svrhe pri korištenju stranice, kako bi Vam omogućili korištenje svih pruženih opcija.
        Vaši osobni podatci čuvaju se u tajnosti, neće ih se distribuirati, objavljivati, davati trećim stranama
        na korištenje niti ih na bilo koji drugi način učiniti dostupnima bilo kojoj trećoj osobi bez Vaše prethodne suglasnosti.</p>
    <h4 class="mb-2">19.1. Privola</h4>
    <p>Svojim potpisom dajem privolu da voditelj obrade podataka u svrhe prikupljanja podataka za:</p>
    <ul class="mb-3">
        <li>procesuiranje upita poslanih kontaktnim opcijama navedenim na web stranici</li>
        <li>promotivne aktivnosti</li>
        <li>statističke analize posjeta web stranici</li>
    </ul>
    <p>smije prikupljati i obrađivati moje osobne podatke, tj. ime, prezime, adresu, poštanski broj, državu,
        adresu e-pošte i broj telefona.</p>
    <p>Ovom privolom potvrđujem da me je voditelj obrade podataka obavijestio o mojim pravima, a to su:</p>
    <ul class="mb-3">
        <li>pravo na dobivanje informacija o mojim osobnim podacima koji su pohranjeni;</li>
        <li>pravo zahtijevanja ispravaka, brisanja ili ograničenja obrade mojih osobnih podataka;</li>
        <li>pravo protivljenja obradi podataka iz razloga vezanih za neki moj opravdani interes, javni interes ili
            profiliranje, osim ako se ne dokaže postojanje nužnih, opravdanih razloga koji nadilaze moje interese,
            prava i slobode ili ako se podaci ne obrađuju u svrhu utvrđivanja, ostvarivanja ili obrane pravnih
            zahtjeva. U slučaju obrade zbog neposrednih promidžbenih svrha uvijek sam ovlašten protiviti se istoj;
        </li>
        <li>pravo na prenosivost podataka;</li>
        <li>pravo na podnošenje žalbe pred tijelom zaduženim za zaštitu podataka;</li>
        <li>mogućnost da u bilo kojem trenutku mogu povući svoju privolu za prikupljanje, obradu i korištenje svojih
            podataka s budućim djelovanjem.
        </li>
    </ul>
    <p>Ukoliko želite ostvariti svoja prava molimo da zahtjev pošaljete na:</p>
    <ul class="mb-3">
        <li>adresu... ili na</li>
        <li>
            adresu e-pošte <a href="...">...</a>.</li>
    </ul>
    <p>Ova privola vrijedi do opoziva ili prestanka obrade zbog ispunjenja svrhe za koju je dana. Osobni će podaci
        biti brisani i njihova će obrada prestati. </p>
    <h3 class="mb-2">20. Elektronska komunikacija</h3>
    <p>Posjećivanjem ove web stranice komunicirate elektronskim putem. Time prihvaćate da svi
        sporazumi, obavijesti, priopćenja i ostali sadržaji koju su Vam dostavljeni elektronskim putem zadovoljavaju
        okvire kao da su ostvareni u pisanom obliku.</p>
    <h3 class="mb-2">21. Izmjena uvjeta i odredbi</h3>
    <p>Web stranica zadržava pravo mijenjanja i ažuriranja ovih uvjeta i odredbi, bez prethodne najave. Svaka
        izmjena biti će objavljena na web stranici. </p>

</div>

</body>

</html>
