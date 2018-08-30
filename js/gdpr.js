window.addEventListener("load", function () {
    if ($('body').attr('data-lang') == 'hr') {
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#038ece",
                    "text": "#ffffff"
                },
                "button": {
                    "background": "#ffffff",
                    "text": "#038ece"
                }
            },
            "content": {
                "message": "Na ovoj stranici koristimo kolačiće kako bismo lakše upravljali stranicom i zbog potrebe analize. Za više informacija o kolačićima pročitajte naša Pravila privatnosti.  Nastavljanjem korištenja ove stranice pristajete na kolačiće.",
                "dismiss": "Prihvaćam",
                "link": "Saznajte više",
                "href": 'nest'
            }
        });
    } else if ($('body').attr('data-lang') == 'en') {
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#038ece",
                    "text": "#ffffff"
                },
                "button": {
                    "background": "#ffffff",
                    "text": "#038ece"
                }
            },
            "content": {
                "message": "We use cookies on this web site to help operate our site and for analytics purposes. To learn more about how we use cookies and your cookie choices, read our Privacy Policy. By continuing to use our site, you are giving us your consent to use cookies.",
                "dismiss": "Accept",
                "link": "Learn more",
                "href": 'nest'
            }
        });
    }
});