import CookieRepository from "./services/CookieRepository";
import 'lightgallery';
import 'modaal';
import 'slick-carousel';


export default class BaseApp {
  constructor() {
    this.sliders = [];
    this.modals = [];
    this.galleries = [];

    this.start();
  }

  start() {
    this.onInit();

    $(document).ready(() => {
      this.onReady();
    });
  }

  onInit() {

  }

  onReady() {
    this.handleCookieNotification();
    this.handleMenuToggle();
    this.handleSliders();
    this.handleModals();
    this.handleGalleries();
  }

  handleMenuToggle() {
    $('#toggle-menu-button').on('click', () => {
      $('#page-header').toggleClass('page-header--opened');
    });
  }

  handleCookieNotification() {
    $('#hide-cookie-notification-btn').on('click', () => {
      CookieRepository.set('hideCookieNotification', true);

      $('#cookie-notification').remove();
    });
  }

  handleSliders() {
    if (this.sliders) {
      for (const slider of this.sliders) {
        slider.element.slick(slider.options);
      }
    }
  }

  handleModals() {
    if (this.modals) {
      for (const modal of this.modals) {
        modal.element.modaal(modal.options);
      }
    }
  }

  handleGalleries() {
    if (this.galleries) {
      for (const gallery of this.galleries) {
        gallery.element.lightGallery(gallery.options);
      }
    }
  }

  addSlider($element, options = {}) {
    this.sliders.push({
      element: $element,
      options: options
    });
  }

  addModal($element, options = {}) {
    this.modals.push({
      element: $element,
      options: options
    });
  }

  addGallery($element, options = {}) {
    this.galleries.push({
      element: $element,
      options: options
    });
  }
}
