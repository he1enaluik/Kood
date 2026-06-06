(function () {
  const SHARED_GALLERY = [
    { src: "pildid/ChatGPT Image Jun 4, 2026, 03_18_14 PM.png", alt: "Mesilane lillel" },
    { src: "pildid/ChatGPT Image Jun 4, 2026, 07_20_29 PM.png", alt: "Mesindustalu mesitarudega" },
    { src: "pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png", alt: "Meedegusteerimine meepurkidega" },
  ];

  const TARUKODA_PRODUCTS = {
    niidumesi: {
      slug: "niidumesi",
      name: "Niidumesi",
      price: 8.9,
      category: "mesi",
      origin_filter: "poltsamaa",
      origin: "Põltsamaa, Jõgevamaa",
      weight: "500 g",
      short_desc: "Õrn ja lilleline maitse, mis peegeldab Eesti suviseid niite.",
      description:
        "Meie niidumesi on kogutud puhtatest mahepõldudest, kus mesilased saavad vabalt lendata lillede vahel. Mesi on kreemjas, kergelt magus ja sobib suurepäraselt tee, jogurti või värskete saiade kõrvale.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png",
      badge: "UUS",
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png", alt: "Niidumesi purk" },
        ...SHARED_GALLERY,
      ],
    },
    metsamesi: {
      slug: "metsamesi",
      name: "Metsamesi",
      price: 8.9,
      category: "mesi",
      origin_filter: "jogevamaa",
      origin: "Jõgevamaa",
      weight: "500 g",
      short_desc: "Tugevama iseloomuga mesi metstaimede nektarist.",
      description:
        "Metsamesi on kogutud Eesti metsade rikkalikust taimestikust. Selle sügavam ja mahedam maitse tuleb pärna-, kase- ja teiste metstaimede nektarist. Sobib suurepäraselt leivale, juustule ja soojadele jookidele.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png",
      badge: "UUS",
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png", alt: "Metsamesi purk" },
        ...SHARED_GALLERY,
      ],
    },
    parnamesi: {
      slug: "parnamesi",
      name: "Pärnamesi",
      price: 8.9,
      category: "mesi",
      origin_filter: "laane",
      origin: "Lääne-Eesti",
      weight: "500 g",
      short_desc: "Sametine tekstuur ning meeldivalt aromaatne järelmaitse.",
      description:
        "Pärnamesi on tuntud oma pehme tekstuuri ja õrna pärnaõite aroomi poolest. See mesi kristalliseerub aeglaselt ja jääb pikaks ajaks pehme. Ideaalne magustoitude ja hommikusöögivalikute kõrvale.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 04_55_14 PM.png",
      badge: "UUS",
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_55_14 PM.png", alt: "Pärnamesi purk" },
        ...SHARED_GALLERY,
      ],
    },
    kinkekarp: {
      slug: "kinkekarp",
      name: "Kinkekarp",
      price: 25.7,
      category: "kinke",
      origin_filter: "poltsamaa",
      origin: "Põltsamaa",
      weight: "1 komplekt",
      short_desc: "Kolm hoolikalt valitud meeliiki kaunis kinkepakendis.",
      description:
        "Kinkekarp sisaldab kolme erinevat Tarukoja meeliiki – niidu-, metsa- ja pärnamesi. Kaunis pakend teeb sellest ideaalse kingituse mesisõpradele ja pereliikmetele.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 05_04_47 PM.png",
      badge: "UUS",
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 05_04_47 PM.png", alt: "Kinkekarp" },
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png", alt: "Niidumesi purk" },
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png", alt: "Metsamesi purk" },
      ],
    },
    "mesilasvaha-kuunal": {
      slug: "mesilasvaha-kuunal",
      name: "Mesilasvaha küünal",
      price: 12.5,
      category: "kunlad",
      origin_filter: "poltsamaa",
      origin: "Põltsamaa",
      weight: "1 tk",
      short_desc: "Käsitööna valmistatud looduslik vahaküünal mee aroomiga.",
      description:
        "Mesilasvaha küünal on valmistatud puhtast mesilasvahast ja annab sooja, meeldiva mee aroomi. Küünal põleb ühtlaselt ja looduslikult, luues hubase atmosfääri igasse ruumi.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png",
      badge: null,
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_50_10 PM.png", alt: "Mesilasvaha küünal" },
        { src: "pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png", alt: "Meedegusteerimine meepurkidega" },
      ],
    },
    "hooajaline-mesi": {
      slug: "hooajaline-mesi",
      name: "Hooajaline mesi",
      price: 9.9,
      category: "hooaeg",
      origin_filter: "jogevamaa",
      origin: "Jõgevamaa",
      weight: "500 g",
      short_desc: "Piiratud koguses erimaitsega mesi vastavalt hooajale.",
      description:
        "Hooajaline mesi on piiratud koguses saadaval erimaitsega sort, mis peegeldab konkreetse korjehooaja lillede ja taimede mitmekesisust. Iga parti on ainulaadne.",
      image: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png",
      badge: null,
      gallery: [
        { src: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png", alt: "Hooajaline mesi purk" },
        ...SHARED_GALLERY,
      ],
    },
  };

  function encodeAssetPath(path) {
    return path
      .split("/")
      .map((part) => encodeURIComponent(part))
      .join("/");
  }

  function formatPrice(price) {
    return price.toFixed(2).replace(".", ",") + " €";
  }

  function getProduct(slug) {
    return TARUKODA_PRODUCTS[slug] || null;
  }

  window.TARUKODA_PRODUCTS = TARUKODA_PRODUCTS;
  window.TarukodaProducts = {
    all: TARUKODA_PRODUCTS,
    get: getProduct,
    encodeAssetPath,
    formatPrice,
  };
})();
