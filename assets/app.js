import "./bootstrap.js";
import canvasConfetti from "canvas-confetti";

document.body.addEventListener("click", () => {
  canvasConfetti();
});

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
