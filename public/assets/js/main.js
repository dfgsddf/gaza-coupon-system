document.addEventListener("DOMContentLoaded", function () {
  const counters = document.querySelectorAll(".counter");

  counters.forEach((counter) => {
    const target = +counter.getAttribute("data-target");
    let count = 0;
    const speed = 200;

    const updateCount = () => {
      const increment = Math.ceil(target / speed);
      if (count < target) {
        count += increment;
        counter.innerText = count.toLocaleString();
        setTimeout(updateCount, 20);
      } else {
        counter.innerText = target.toLocaleString();
      }
    };

    updateCount();
  });
});
