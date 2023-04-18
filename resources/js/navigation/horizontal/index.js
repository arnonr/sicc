// import appAndPages from './app-and-pages'
// import charts from './charts'
// import dashboard from './dashboard'
// import forms from './forms'
// import others from './others'
// import uiElements from './ui-elements'

import aboutUs from "./about-us";
import ContactUs from "./contact-us";
import home from "./home";
import newsAndAnnoucements from "./news-and-annoucements";
import services from "./services";

// export default [...dashboard, ...appAndPages, ...uiElements, ...forms, ...charts, ...others]
export default [
  ...home,
  ...services,
  ...newsAndAnnoucements,
  ...aboutUs,
  ...ContactUs,
];
