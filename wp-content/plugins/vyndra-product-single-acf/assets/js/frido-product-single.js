document.addEventListener('DOMContentLoaded', function () {
    var mainImg = document.getElementById('vyndra-main-img');
    var thumbButtons = document.querySelectorAll('.thumb[data-full-src]');
    if (mainImg && thumbButtons.length) {
        thumbButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                thumbButtons.forEach(function (item) {
                    item.classList.remove('active');
                });
                button.classList.add('active');
                mainImg.src = button.getAttribute('data-full-src');
                if (button.querySelector('img')) {
                    mainImg.alt = button.querySelector('img').alt || mainImg.alt;
                }
            });
        });
    }

    var ctaStack = document.querySelector('.cta-stack');
    var stickyAtc = document.querySelector('.sticky-atc');
    if (ctaStack && stickyAtc) {
        var observer = new IntersectionObserver(function (entries) {
            if (!entries[0].isIntersecting) {
                stickyAtc.classList.add('visible');
            } else {
                stickyAtc.classList.remove('visible');
            }
        }, { threshold: 0 });

        observer.observe(ctaStack);
    }

    document.querySelectorAll('.vyndra-faq-q').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.closest('.vyndra-faq-item');
            if (item) {
                item.classList.toggle('open');
            }
        });
    });

    document.querySelectorAll('.tab-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var tab = button.getAttribute('data-tab');
            if (!tab) {
                return;
            }

            document.querySelectorAll('.tab-button').forEach(function (btn) {
                btn.classList.toggle('active', btn === button);
            });
            document.querySelectorAll('.tab-panel').forEach(function (panel) {
                panel.classList.toggle('active', panel.getAttribute('data-tab') === tab);
            });
        });
    });

    document.querySelectorAll('.accordion-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.closest('.accordion-item');
            if (!item) {
                return;
            }
            item.classList.toggle('open');
            var content = item.querySelector('.accordion-content');
            if (content) {
                if (item.classList.contains('open')) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                } else {
                    content.style.maxHeight = null;
                }
            }
        });
    });

    document.querySelectorAll('.accordion-item.open .accordion-content').forEach(function (content) {
        content.style.maxHeight = content.scrollHeight + 'px';
    });

    document.querySelectorAll('.variant-pill').forEach(function (button) {
        button.addEventListener('click', function () {
            var variantType = button.getAttribute('data-variant');
            if (!variantType) {
                return;
            }

            document.querySelectorAll('.variant-pill[data-variant="' + variantType + '"]').forEach(function (pill) {
                pill.classList.remove('active');
            });
            button.classList.add('active');
        });
    });

    var stickyButton = document.querySelector('.sticky-btn');
    if (stickyButton) {
        stickyButton.addEventListener('click', function () {
            var cartForm = document.querySelector('form.cart');
            if (cartForm) {
                cartForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
});
