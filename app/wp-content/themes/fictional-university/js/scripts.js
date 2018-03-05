// 3rd party packages from NPM
import $ from 'jquery';
const jQuery = $;
import slick from 'slick-carousel';

// Our modules / classes
import MobileMenu from './modules/MobileMenu';
import HeroSlider from './modules/HeroSlider';
import GoogleMap from './modules/GoogleMap';
import Search from './modules/Search';
import MyNotes from './modules/MyNotes';
import Like from './modules/Like';
import BarbaSetup from './modules/Barba';

// Instantiate a new object using our modules/classes
let heroSlider,
 search,
 myNotes,
 like;

function init() {
  const mobileMenu = new MobileMenu();
  const googleMap = new GoogleMap();
  search = new Search();  
  myNotes = new MyNotes();
  heroSlider = new HeroSlider();
  like = new Like();
}

function deinit() {
  heroSlider.destroy();
  search.destroy();
}

const barba = new BarbaSetup(init, deinit);


init();



