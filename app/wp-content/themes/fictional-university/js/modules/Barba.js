import $ from 'jquery';
import Barba from 'barba.js';

const oldPreventCheck = Barba.Pjax.preventCheck.bind(Barba.Pjax);
Barba.Pjax.preventCheck = function(evt, element) {
  const res = oldPreventCheck(evt, element);
  if (res) {
    var href = $(element).attr('href');
    if (!href.includes('wp-admin') && !href.includes('wp-login') && !href.includes('wp-signup')) {
      return true;
    }
  }
  return false;
}

// const HideShowTransition = Barba.BaseTransition.extend({
//   start: function() {
//     this.newContainerLoading.then(this.finish.bind(this));
//   },

//   finish: function() {
//     document.body.scrollTop = 0;
//     this.done();
//   }
// });

let init;

var FadeTransition = Barba.BaseTransition.extend({
  start: function() {
    /**
     * This function is automatically called as soon the Transition starts
     * this.newContainerLoading is a Promise for the loading of the new container
     * (Barba.js also comes with an handy Promise polyfill!)
     */

    // As soon the loading is finished and the old page is faded out, let's fade the new page
    Promise
      .all([this.newContainerLoading, this.fadeOut()])
      .then(this.fadeIn.bind(this));
  },

  fadeOut: function() {
    /**
     * this.oldContainer is the HTMLElement of the old Container
     */
    

    return $(this.oldContainer).animate({ opacity: 0 }, 200).promise();
  },

  fadeIn: function() {
    /**
     * this.newContainer is the HTMLElement of the new Container
     * At this stage newContainer is on the DOM (inside our #barba-container and with visibility: hidden)
     * Please note, newContainer is available just after newContainerLoading is resolved!
     */

    var _this = this;
    var $el = $(this.newContainer);
    $(document).scrollTop(0);


    const $newHTML = $(`<div>${Barba.Pjax.Dom.currentHTML}</div>`);
    // replace the header with the new one
    $('header').html($newHTML.find('header').html());
    $(this.oldContainer).hide();
    // replace admin toolbar
    $('#wpadminbar').html($newHTML.find('#wpadminbar').html());

    init();

    $el.css({
      visibility : 'visible',
      opacity: 0,
    });
    
    $el.animate({ opacity: 1 }, 200, function() {
      /**
       * Do not forget to call .done() as soon your transition is finished!
       * .done() will automatically remove from the DOM the old Container
       */

      _this.done();
    });
  }
});


Barba.Pjax.getTransition = function() {
  return FadeTransition;
};

class BarbaSetup {
  constructor(_init, deinit) {
    // this.init = init;
    init = _init;
    this.deinit = deinit;

    Barba.Pjax.start();
    this.events();
  }

  events() {
    // Barba.Dispatcher.on('newPageReady', (currentStatus, oldStatus, container) => {
    //   // need setTimeout to bind events to replaced header
    //   setTimeout(() => {
    //     this.init();
    //   }, 400);
    // });
    
    Barba.Dispatcher.on('linkClicked', () => {
      this.deinit();
    });    
  }
}

export default BarbaSetup;
