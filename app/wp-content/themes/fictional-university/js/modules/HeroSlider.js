import $ from 'jquery';

class HeroSlider {
  constructor() {
    this.els = $(".hero-slider");
    this.initSlider();
  }

  initSlider() {
    this.els.slick({
      autoplay: true,
      arrows: false,
      dots: true
    });
  }

  destroy() {
    this.els.slick('unslick');
  }
}

export default HeroSlider;