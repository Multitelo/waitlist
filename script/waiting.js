function joinWaitlistar() {
    var join = document.getElementById("joinar");
    var joinw = document.getElementById("joinw");
    var joinap = document.getElementById("joinap");

    if (join.style.display === "none") {
      join.style.display = "flex";
      joinw.style.display = "none";
    }
    if (joinap.style.display === "flex") {
      joinap.style.display = "none";
    }
    
};


function joinWaitlistap() {
  var join = document.getElementById("joinap");
  var joinw = document.getElementById("joinw");
  var joinar = document.getElementById("joinar");

  if (join.style.display === "none") {
    join.style.display = "flex";
    joinw.style.display = "none";
  }
  if (joinar.style.display === "flex") {
    joinar.style.display = "none";
  }
};


//For showing slide on homepage
let slideIndexR = 0;
let slideIndexP = 0;

showSlides("resea", "fade", slideIndexR);
showSlides("parti", "fade", slideIndexP);

function showSlides(containerId, className, index) {
    let i;
    const slides = document.getElementById(containerId).getElementsByClassName(className);
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    index++;
    if (index > slides.length) {
        index = 1;
    }
    slides[index - 1].style.display = "block";
    setTimeout(() => showSlides(containerId, className, index), 3000);
}

// for referral box
function referb() {
  var refb = document.getElementById("refb");
  var brefer = document.getElementById("brefer");
  // var joinar = document.getElementById("joinar");

  if (brefer.style.display === "none") {
    brefer.style.display = "block";
    refb.style.display = "none";
  }
}


 //for copy and of link
function copylink() {
  const inputField = document.getElementById("myinput");

  // Select the text inside the input field
  inputField.select();
  inputField.setSelectionRange(0, 99999); // For mobile devices

  try {
    // Use the Clipboard API to copy the selected text to the clipboard
    navigator.clipboard.writeText(inputField.value)
        .then(() => {
            alert("Text copied: " + inputField.value);
        })
        .catch(error => {
            console.error("Copying failed:", error);
        });
} catch (error) {
    console.error("Copying failed:", error);
}
inputField.setSelectionRange(0, 0);
}
//share
async function sharelink() {
  const inputField = document.getElementById("myinput");

  try {
      await navigator.share({
          title: "Shared Text",
          text: inputField.value,
      });
  } catch (error) {
      console.error("Sharing failed:", error);
  }
}
