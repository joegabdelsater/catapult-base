document.addEventListener('DOMContentLoaded', function() {
    // Select all the arrow-up elements
    const arrowUps = document.querySelectorAll('.js-arrow-up');
    arrowUps.forEach(arrowUp => {
        arrowUp.addEventListener('click', function() {
            const modelName = this.getAttribute('data-arrow').replace('arrow-up-', '');
            // Hide the arrow up
            this.style.display = 'none';
            // Show the arrow down
            document.querySelector(`.js-arrow-down[data-arrow="arrow-up-${modelName}"]`)
                .style.display = 'grid';
            // Hide the container
            document.querySelector(`.js-${modelName}-container`).style.display = 'none';
        });
    });

    // Select all the arrow-down elements
    const arrowDowns = document.querySelectorAll('.js-arrow-down');
    arrowDowns.forEach(arrowDown => {
        arrowDown.addEventListener('click', function() {
            const modelName = this.getAttribute('data-arrow').replace('arrow-up-','' ); // Note: your data-arrow attributes for down are currently the same as for up, which might be a mistake.
            // Show the arrow up
            document.querySelector(`.js-arrow-up[data-arrow="arrow-up-${modelName}"]`).style
                .display = 'grid';
            // Hide the arrow down
            this.style.display = 'none';
            // Show the container
            document.querySelector(`.js-${modelName}-container`).style.display = 'grid';
        });
    });
});