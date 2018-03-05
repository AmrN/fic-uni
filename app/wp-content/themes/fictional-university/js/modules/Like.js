import $ from 'jquery';

const ROOT_URL = university_data.root_url;
const NONCE = university_data.nonce;

class Like {
  constructor() {
    this.events();
  }

  events() {
    $('.like-box').on('click', this.ourClickDispatcher.bind(this));
  }

  ourClickDispatcher(ev) {
    const $currentLikeBox = $(ev.target).closest('.like-box');
    if ($currentLikeBox.attr('data-exists') == 'yes') {
      this.deleteLike($currentLikeBox);
    } else {
      this.createLike($currentLikeBox);
    }
  }

  createLike($currentLikeBox) {
    const professorId = $currentLikeBox.attr('data-professor');
    $.ajax({
      url: `${ROOT_URL}/wp-json/university/v1/manageLike`,
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', NONCE);
      },
      data: {
        professorId: professorId,
      },
      type: 'POST',
      success: (res) => {
        const $countEl = $currentLikeBox.find('.like-count');
        $currentLikeBox.attr('data-exists', 'yes');
        $currentLikeBox.attr('data-like', res);
        $countEl.html(parseInt($countEl.html(), 10) + 1);
        console.log(res);
      },
      error: (err) => {
        console.log(err);
      },
    });
  }

  deleteLike($currentLikeBox) {
    $.ajax({
      url: `${ROOT_URL}/wp-json/university/v1/manageLike`,
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', NONCE);
      },
      data: {
        'like': $currentLikeBox.attr('data-like'),
      },
      type: 'DELETE',
      success: (res) => {
        const $countEl = $currentLikeBox.find('.like-count');
        $currentLikeBox.attr('data-exists', 'no');
        $currentLikeBox.attr('data-like', '');
        $countEl.html(parseInt($countEl.html(), 10) - 1);
        console.log(res);
      },
      error: (err) => {
        console.log(err);
      },
    });
  }
}

export default Like;