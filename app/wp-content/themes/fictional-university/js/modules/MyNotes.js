import $ from 'jquery';

const ROOT_URL = university_data.root_url;
const NONCE = university_data.nonce;

class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    $('#my-notes').on('click', '.delete-note', this.deleteNote.bind(this));
    $('#my-notes').on('click', '.edit-note', this.editNote.bind(this));
    $('#my-notes').on('click', '.update-note', this.updateNote.bind(this));
    $('.submit-note').on('click', this.createNote.bind(this));
  }

  deleteNote(ev) {
    const $thisNote = $(ev.target).parents('li');
    const id = $thisNote.data('id');

    $.ajax({
      url: `${ROOT_URL}/wp-json/wp/v2/note/${id}`,
      type: 'DELETE',
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', NONCE);
      },
      success: (res) => {
        console.log('congrats:', res);
        $thisNote.slideUp(null, null, () => $thisNote.remove());
        if (Number.parseInt(res.userNoteCount) <= 4) {
          $('.note-limit-message').removeClass('active');
        }
      },
      error: (err) => {
        console.log('sorry:', err);
      },
    });
  }

  editNote(ev) {
    const $thisNote = $(ev.target).parents('li');
    if ($thisNote.data('state') === 'editable') {
      this.makeNoteReadonly($thisNote);
      
    } else {
      this.makeNoteEditable($thisNote);
     
    }
  }

  makeNoteEditable(note) {
    const $thisNote = $(note);
    $thisNote.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
    $thisNote.find('.note-title-field, .note-body-field')
      .removeAttr('readonly')
      .addClass('note-active-field');
    $thisNote.find('.update-note').addClass('update-note--visible');
    $thisNote.data('state', 'editable');
  }

  makeNoteReadonly(note) {
    const $thisNote = $(note);
    $thisNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    $thisNote.find('.note-title-field, .note-body-field')
      .attr('readonly', 'readonly')
      .removeClass('note-active-field');
    $thisNote.find('.update-note').removeClass('update-note--visible');
    $thisNote.data('state', 'readonly');
  }

  updateNote(ev) {
    const $thisNote = $(ev.target).parents('li');
    const id = $thisNote.data('id');
    const ourUpdatedPost = {
      title: $thisNote.find('.note-title-field').val(),
      content: $thisNote.find('.note-body-field').val()
    };
    $.ajax({
      url: `${ROOT_URL}/wp-json/wp/v2/note/${id}`,
      type: 'POST',
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', NONCE);
      },
      success: (res) => {
        console.log('congrats:', res);
        this.makeNoteReadonly($thisNote);
      },
      error: (err) => {
        console.log('sorry:', err);
      },
      data: ourUpdatedPost,
    });
  }


  createNote(ev) {
    const ourNewPost = { 
      title: $('.new-note-title').val(),
      content: $('.new-note-body').val(),
      status: 'private'
    };

    $.ajax({
      url: `${ROOT_URL}/wp-json/wp/v2/note`,
      type: 'POST',
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', NONCE);
      },
      success: (res) => {
        console.log('congrats:', res);
        $('.new-note-title').val('');
        $('.new-note-body').val('');
        this.insertNewNote({id: res.id, title: res.title.raw, body: res.content.raw});
      },
      error: (err) => {
        console.log('sorry:', err);
        if (err.responseText == 'You have reached your note limit.') {
          $('.note-limit-message').addClass('active');
        }
      },
      data: ourNewPost,
    });
  }

  insertNewNote({id, title, body}) {
    
    $(`
      <li data-id="${id}">
        <input readonly class="note-title-field" type="text" value="${title}">
        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
        <textarea readonly class="note-body-field" name="" id="" cols="30" rows="10">${body}</textarea>
        <span class="btn btn--blue btn--small update-note"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
      </li>
    `).prependTo('#my-notes').hide().fadeIn();
  }
}

export default MyNotes;