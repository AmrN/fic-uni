import $ from 'jquery';

const ROOT_URL = university_data.root_url;

class Search {
  constructor() {
    this.addSearchHTML();
    this.$openButton = $('.js-search-trigger');
    this.$closeButton = $('.search-overlay__close');
    this.$searchOverlay = $('.search-overlay');
    this.$searchField = $('#search-term');
    this.$resultsDiv = $('#search-overlay__results');
    this.overlayActiveClass = 'search-overlay--active';
    this.isOpen = false;
    this.isSpinnerVisible = false;
    this.typingTimer;
    this.previousValue;
    this.events();
  }

  events() {
    this.$openButton.on('click', this.openOverlay.bind(this));
    this.$closeButton.on('click', this.closeOverlay.bind(this));
    this.$searchField.on('keyup', this.typingLogic.bind(this));
    $(document).on('keydown.search', this.keyPressDispatcher.bind(this));
  }

  destroy() {
    this.closeOverlay();
    this.$searchOverlay.remove();
    this.$openButton.off('click');
    $(document).off('keydown.search');
  }

  keyPressDispatcher(e) {
    if (e.key.toLowerCase() === 's' && !this.isOpen && !$('input, textarea').is(':focus')) {
      this.openOverlay();
    }
    
    if (this.isOpen && e.key === 'Escape') {
      this.closeOverlay();
    }
  }

  openOverlay(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    this.$searchOverlay.addClass(this.overlayActiveClass);
    $('body').addClass('body-no-scroll');
    setTimeout(() => {
      this.$searchField.focus();
    }, 100);
    this.isOpen = true;
  }

  closeOverlay() {
    this.$searchOverlay.removeClass(this.overlayActiveClass);
    $('body').removeClass('body-no-scroll');
    this.$searchField.val('');
    this.isOpen = false;
  }

  typingLogic(e) {
    if (this.previousValue !== this.$searchField.val()) {
      clearTimeout(this.typingTimer);

      if (this.$searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.$resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 400);
        this.previousValue = this.$searchField.val();
      } else {
        this.$resultsDiv.html('');
        this.isSpinnerVisible = false;
      }
    }
  }

  getResults() {
    $.getJSON(`${ROOT_URL}/wp-json/university/v1/search?term=${this.$searchField.val()}`)
      .then((results) => {
        // const combinedResults = [...postsRes[0], ...pagesRes[0]];
        this.setResults(results);
      }, (err) => { 
        this.$resultsDiv.html(`<p>Unexpected error, please try again :(</p>`);
      });

    // $.when(
    //   $.getJSON(`${ROOT_URL}/wp-json/wp/v2/posts?search=${this.$searchField.val()}`),
    //   $.getJSON(`${ROOT_URL}/wp-json/wp/v2/pages?search=${this.$searchField.val()}`)
    // )
    //   .then((postsRes, pagesRes) => {
    //     const combinedResults = [...postsRes[0], ...pagesRes[0]];
    //     this.setResults(combinedResults);
    //   }, (err) => {
    //     this.$resultsDiv.html(`<p>Unexpected error, please try again :(</p>`);
    //   });
  }

  setResults(posts) {
    const generalInfoPosts = posts['generalInfo'];
    const programsPosts = posts['programs'];
    const professorsPosts = posts['professors'];
    const campusesPosts = posts['campuses'];
    const eventsPosts = posts['events'];
    this.isSpinnerVisible = false;
    this.$resultsDiv.html(`
      <div class="row">
        <div class="one-third">
          <h2 class="search-overlay__section-title">General Information</h2>
            ${(generalInfoPosts && generalInfoPosts.length > 0) ?
              `<ul class="link-list min-list">
                ${generalInfoPosts.map((p) => `
                  <li>
                    <a href="${p.permalink}">${p.title}</a>
                    ${p.post_type === 'post' ? `by <em>${p.author_name}</em>` : ''} 
                  </li>
                `).join('')}
              </ul>`
              :
              `<p>No general information matches that search</p>`}
        </div>
        <div class="one-third">
          <h2 class="search-overlay__section-title">Programs</h2>
            ${(programsPosts && programsPosts.length > 0) ?
              `<ul class="link-list min-list">
                ${programsPosts.map((p) => `
                  <li>
                    <a href="${p.permalink}">${p.title}</a>
                  </li>
                `).join('')}
              </ul>`
              :
              `<p>No programs match that search. <a href="${ROOT_URL}/programs">View all programs</a></p>`}
          <h2 class="search-overlay__section-title">Professors</h2>
            ${(professorsPosts && professorsPosts.length > 0) ?
              `<ul class="professor-cards">
                ${professorsPosts.map((p) => `
                  <li class="professor-card__list-item">
                    <a class="professor-card" href="${p.permalink}">
                      <img class="professor-card__image" src="${p.thumbnail}" alt="${p.title}">
                      <span class="professor-card__name">${p.title}</span>
                    </a>
                  </li>
                  <br>
                `).join('')}
              </ul>`
              :
              `<p>No professors match that search</p>`}
        </div>
        <div class="one-third">
          <h2 class="search-overlay__section-title">Campuses</h2>
            ${(campusesPosts && campusesPosts.length > 0) ?
              `<ul class="link-list min-list">
                ${campusesPosts.map((p) => `
                  <li>
                    <a href="${p.permalink}">${p.title}</a>
                  </li>
                `).join('')}
              </ul>`
              :
              `<p>No campuses match that search. <a href="${ROOT_URL}/campuses">View all campuses</a></p>`}
          <h2 class="search-overlay__section-title">Events</h2>
            ${(eventsPosts && eventsPosts.length > 0) ?
              eventsPosts.map(p => `
              <div class="event-summary">
                <a class="event-summary__date t-center" href="${p.permalink}">
                  <span class="event-summary__month">
                    ${p.month}
                  </span>
                  <span class="event-summary__day">
                    ${p.day}
                  </span>  
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny"><a href="${p.permalink}">${p.title}</a></h5>
                </div>
                <p>${p.excerpt}<a href="${p.permalink}" class="nu gray">Learn more</a></p>
              </div>`).join('')
              :
              `<p>No events match that search. <a href="${ROOT_URL}/events">View all events</a></p>`}
        </div>
      </div>
    `);
  }

  addSearchHTML() {
    $('body').append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `);
  }
}

export default Search;