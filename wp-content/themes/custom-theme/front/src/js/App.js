import BaseApp from "./BaseApp";
import PDFObject from 'pdfobject';
import SmoothScroll from '../../node_modules/smooth-scroll/dist/smooth-scroll.min';

export default class App extends BaseApp {
  static start() {
    this.app = new App();
  }

  onInit() {
    this.handlePageSlider();
    super.onInit();
  }

  onReady() {
    this.handleDownloadPDF();
    this.handleLoader();
    this.handleGalleryBanner();
    this.handleContactPeopleSlider();
    this.handleDownloadPDF();
    this.handleSmoothScroll();

    super.onReady();
  }

  handlePageSlider() {
    this.addSlider($('.page-slider'), {
      dots: true,
      infinite: true,
      speed: 500,
      fade: true,
      cssEase: 'linear',
      autoplay: true,
      autoplaySpeed: 4500
    });
  }

  handleContactPeopleSlider() {
    this.addSlider($('.contact-people__slider'), {
      dots: true,
      infinite: true,
      speed: 500,
      slidesToShow: 4,
      slidesToScroll: 4,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }

  handleGalleryBanner() {
    this.addGallery($('.gallery-banner__gallery'), {});
  }

  handleLoader() {
    setTimeout(() => {
      $('.loader').addClass('hidden');
    }, 500);
  }

  handleDownloadPDF() {
    const handlePdfLinks = () => {
      const $downloadLinks = $('#download-list a');

      if ($downloadLinks && $downloadLinks.length > 0) {
        PDFObject.embed($downloadLinks[0].href, "#pdf_view");

        $downloadLinks.on('click', e => {
          e.preventDefault();

          PDFObject.embed(e.target.href, "#pdf_view");
        });
      }
    };

    if (window.innerWidth > 991) {
      handlePdfLinks();
    }

    $(window).on("resize", e => {
      if (window.innerWidth > 991) {
        handlePdfLinks();
      }
    });
  }

  handleSmoothScroll() {
    new SmoothScroll('a[href*="#"]', {offset: 80});
  }
}
