/* JS pour le bouton d'accessibilité contraste élevé + taille de texte */

document.addEventListener('DOMContentLoaded', function() {
    createAccessibilityPanel();
    createScrollToTopButton();
    loadAccessibilitySettings();
    createResponsiveBurgerMenu();
    createLanguageSwitcher();
});

/* traduction du site en anglais */
const translations = {
    en: {
        "le club famille - nous contacter": "Club Famille - Contact Us",
        "le club famille - connexion": "Club Famille - Login",
        "le club famille - inscription": "Club Famille - Register",
        "a propos de l'association": "About us",
        "calendrier": "Calendar",
        "nous contacter": "Contact us",
        "déconnexion": "Logout",
        "connexion": "Login",
        "formulaire de contact": "Contact form",
        "les champs marqués d’un astérisque (*) sont obligatoires.": "Fields marked with an asterisk (*) are required.",
        "*nom": "*Name",
        "*email": "*Email",
        "*message": "*Message",
        "votre nom": "Your name",
        "votre email": "Your email",
        "votre message": "Your message",
        "envoyer": "Send",
        "inscription": "Register",
        "se connecter": "Log in",
        "déjà un compte?": "Already have an account?",
        "vous avez déjà un compte?": "Already have an account?",
        "aller à la page de connexion": "Go to the login page",
        "veuillez entrer votre email et votre mot de passe.": "Please enter your email and password.",
        "mot de passe incorrect.": "Incorrect password.",
        "email non trouvé.": "Email not found.",
        "les champs obligatoires doivent être remplis.": "Required fields must be filled.",
        "le mot de passe doit contenir au moins 6 caractères.": "Password must be at least 6 characters long.",
        "les mots de passe ne correspondent pas.": "Passwords do not match.",
        "adresse email invalide.": "Invalid email address.",
        "cet email est déjà utilisé.": "This email is already in use.",
        "inscription réussie! vous pouvez maintenant vous connecter.": "Registration successful! You can now log in.",
        "erreur lors de l'inscription. veuillez réessayer.": "Error during registration. Please try again.",
        "nous sommes sur place au 16 avenue de laon, 51100 reims": "We are on site at 16 Avenue de Laon, 51100 Reims",
        "sur instagram @clubfamille_lamitie": "On Instagram @clubfamille_lamitie",
        "par téléphone au 03 10 16 10 96": "By phone at 03 10 16 10 96",
        "nous vous répondrons bientôt.": "We will answer you soon.",
        "a propos de nous": "About us",
        "liens": "Links",
        "nos missions": "Our mission",
        "soutien aux jeunes aidants": "Support for young carers",
        "accompagnement à la parentalité": "Parenting support",
        "l'équipe du club famille": "The Club Famille team",
        "programme « ambassadeurs santé mentale »": "Mental Health Ambassadors Program",
        "une cheffe de service": "A Service Manager",
        "une infirmière diplômée d'état": "A State-Certified Nurse",
        "un moniteur éducateur": "A Special Education Instructor",
        "une secrétaire": "A Secretary",
        "co-supervision avec unis-cité : ensemble, nous mettons en œuvre le programme « ambassadeurs santé mentale », une initiative nationale visant à former des jeunes volontaires en service civique afin qu'ils deviennent des acteurs de la sensibilisation à la santé mentale, le soutien par les pairs et la déstigmatisation. ce programme comprend :": "Co-supervised with Unis-Cité: Together we run the Mental Health Ambassadors program, a national initiative training young civic service volunteers to become actors of mental health awareness, peer support, and destigmatization. This program includes:",
        "la formation des jeunes : les jeunes volontaires sont formés pour comprendre les enjeux de la santé mentale, reconnaître les signes de détresse psychologique et apporter un soutien de premier niveau à leurs pairs.": "Youth training: Volunteers are trained to understand mental health issues, recognize signs of psychological distress, and provide first-level support to their peers.",
        "des actions de sensibilisation : les ambassadeurs mènent des actions de sensibilisation dans divers contextes, y compris les écoles, les universités, et au sein de la communauté, pour briser les tabous et promouvoir une meilleure compréhension de la santé mentale.": "Awareness actions: Ambassadors carry out awareness activities in various settings, including schools, universities, and the community, to break taboos and promote a better understanding of mental health.",
        "support par les pairs : en tant qu'ambassadeurs, ils offrent un soutien aux jeunes rémois, créant ainsi un environnement plus inclusif et empathique pour tous.": "Peer support: As ambassadors, they offer support to young people in Reims, creating a more inclusive and empathetic environment for everyone.",
        "chaque mois, nous changeons notre programme d'activités, pour répondre aux besoins exprimés par les bénéficiaires.": "Each month, we update our activity program to respond to the needs expressed by beneficiaries.",
        "exemple d'activités :": "Example activities:",
        "ateliers créatifs : nous organisons des ateliers de musique, de photographie, ou encore de création de bandes dessinées pour stimuler l'expression créative, etc.": "Creative workshops: we organize music, photography, and comic book creation workshops to stimulate creative expression, among others.",
        "bien-être : des séances de sophrologie sont proposées pour initier les personnes accompagnées à la relaxation et à la gestion du stress.": "Well-being: sophrology sessions are offered to introduce participants to relaxation and stress management.",
        "activités libres : les personnes choisissent leurs propres activités pendant les temps libres, ce qui peut inclure des loisirs personnels, de la lecture, ou des discussions autour de la parentalité.": "Free activities: participants choose their own activities during free time, which may include personal hobbies, reading, or discussions about parenting.",
        "promenades en nature : des sorties sont organisées pour permettre aux membres de profiter des bienfaits de la nature, accompagnés par nos compagnons canins, jérôme, coffee ou croc dur.": "Nature walks: outings are organized to allow members to enjoy the benefits of nature, accompanied by our canine companions Jérôme, Coffee, or Croc Dur.",
        "le club famille": "Club Famille",
        "accessibilité": "Accessibility",
        "contraste élevé": "High contrast",
        "taille de texte :": "Text size:",
        "normal": "Normal",
        "grand": "Large",
        "réinitialiser": "Reset",
        "ouvrir le panneau d'accessibilité": "Open accessibility panel",
        "remonter en haut de la page": "Scroll to top",
        "title-home": "Club Famille",
        "title-login": "Club Famille - Login",
        "title-register": "Club Famille - Register",
        "title-contact": "Club Famille - Contact Us",
        "title-calendar": "Club Famille",
        "nav-about": "About us",
        "nav-calendar": "Calendar",
        "nav-contact": "Contact us",
        "footer-links": "Links",
        "footer-about": "About us",
        "footer-calendar": "Calendar",
        "footer-contact": "Contact us",
        "home-intro-1": "Initiated by the L'Amitié association in Reims and supported by ARS Grand Est, Club Famille was launched in March 2023 to create a resource and sharing space for a specific audience: young carers and their parents with psychiatric disorders.",
        "home-intro-2": "Our initiative aims to address a little-supported need for parenting support for parents under psychiatric care, focused on developing psychosocial skills, peer social interactions, and protection and wellbeing factors for children. We focus on support, education, prevention and assistance through non-clinical means and sometimes as a complement to the traditional care pathway; we are not a medical service but a 'caring' service.",
        "home-missions-title": "Our mission",
        "home-support-young-title": "Support for young carers",
        "home-support-young-text": "Young carers often have additional responsibilities: they manage their own lives while helping a sick or disabled family member in their daily life. Recognizing the complexity of their role, Club Famille is committed to offering them a place of support, listening, information and sharing experiences.",
        "home-parental-support-title": "Parenting support",
        "home-parental-support-text": "We provide parents with a friendly space where they can address their parenting challenges. Our workshops, support groups and resources create a community of support and exchange.",
        "home-team-title": "The Club Famille team",
        "home-team-item-1": "A Service Manager",
        "home-team-item-2": "A State-Certified Nurse",
        "home-team-item-3": "A Special Education Instructor",
        "home-team-item-4": "A Secretary",
        "home-program-title": "Mental Health Ambassadors Program",
        "home-program-text": "Co-supervised with <b>Unis-Cité</b>: Together we implement the Mental Health Ambassadors program, a national initiative training young civic service volunteers to become actors of mental health awareness, peer support and destigmatization. This program includes:",
        "home-program-item-1": "<b>Youth training</b>: Volunteers are trained to understand mental health issues, recognize signs of psychological distress and provide first-level support to their peers.",
        "home-program-item-2": "<b>Awareness actions</b>: Ambassadors carry out awareness activities in various contexts, including schools, universities and the community, to break taboos and promote a better understanding of mental health.",
        "home-program-item-3": "<b>Peer support</b>: As ambassadors, they provide support to young people in Reims, creating a more inclusive and empathetic environment for everyone.",
        "login-title": "Login",
        "login-required-note": "Fields marked with an asterisk (*) are required.",
        "login-email-label": "*Email",
        "login-email-placeholder": "Email",
        "login-password-label": "*Password",
        "login-password-placeholder": "Password",
        "login-submit": "Login",
        "login-register-note": "Not yet registered? <a href=\"register.php\" style=\"color: #E2C6F3; text-decoration: none; font-weight: 600;\">Create an account</a>",
        "login-register-link": "Create an account",
        "register-title": "Register",
        "register-firstname-label": "*First Name",
        "register-firstname-placeholder": "First Name",
        "register-lastname-label": "*Name",
        "register-lastname-placeholder": "Name",
        "register-email-label": "*Email",
        "register-email-placeholder": "Email",
        "register-password-label": "*Password",
        "register-password-placeholder": "Password (minimum 6 characters)",
        "register-password-confirm-label": "*Confirm password",
        "register-password-confirm-placeholder": "Confirm password",
        "register-submit": "Register",
        "register-have-account": "Already have an account? <a href=\"login.php\" style=\"color: #E2C6F3; text-decoration: none; font-weight: 600;\">Log in</a>",
        "register-login-link": "Log in",
        "contact-title": "Contact us",
        "contact-location": "We are on site at <b>16 Avenue de Laon, 51100 Reims</b>",
        "contact-instagram": "On Instagram <b>@clubfamille_lamitie</b>",
        "contact-phone": "By phone at <b>03 10 16 10 96</b>",
        "contact-form-title": "Contact form",
        "contact-required-note": "Fields marked with an asterisk (*) are required.",
        "contact-name-label": "*Name",
        "contact-name-placeholder": "Your name",
        "contact-email-label": "Email",
        "contact-email-label-required": "*Email",
        "contact-email-placeholder": "Your email",
        "contact-message-label": "*Message",
        "contact-message-placeholder": "Your message",
        "contact-submit": "Send",
        "calendar-intro-1": "Each month, we update our activity program to respond to the needs expressed by beneficiaries.",
        "calendar-intro-2": "Example activities:",
        "calendar-item-1": "<b>Creative workshops</b>: we organize music, photography, and comic book creation workshops to stimulate creative expression, among others.",
        "calendar-item-2": "<b>Well-being</b>: sophrology sessions are offered to introduce participants to relaxation and stress management.",
        "calendar-item-3": "<b>Free activities</b>: participants choose their own activities during free time, which may include personal hobbies, reading, or discussions about parenting.",
        "calendar-item-4": "<b>Nature walks</b>: outings are organized to allow members to enjoy the benefits of nature, accompanied by our canine companions Jérôme, Coffee or Croc Dur."
    }
};

function normalizeText(text) {
    return text.replace(/\s+/g, ' ').trim().toLowerCase();
}

function createLanguageSwitcher() {
    const headerTarget = document.querySelector('.header-right') || document.querySelector('.header');
    if (!headerTarget) {
        return;
    }

    const switcher = document.createElement('button');
    switcher.className = 'language-switcher';
    switcher.type = 'button';
    switcher.setAttribute('aria-label', 'Changer la langue / Change language');

    const savedLanguage = localStorage.getItem('siteLanguage') || 'fr';
    switcher.textContent = savedLanguage === 'en' ? 'FR' : 'EN';
    switcher.addEventListener('click', function() {
        const newLanguage = document.documentElement.lang === 'en' ? 'fr' : 'en';
        applyLanguage(newLanguage);
        switcher.textContent = newLanguage === 'en' ? 'FR' : 'EN';
    });

    headerTarget.appendChild(switcher);
    applyLanguage(savedLanguage);
}

function applyLanguage(lang) {
    const allTranslatable = document.querySelectorAll('[data-translate-key]');
    document.documentElement.lang = lang;
    localStorage.setItem('siteLanguage', lang);

    allTranslatable.forEach(el => {
        const key = el.dataset.translateKey;
        if (!key) {
            return;
        }

        if (!el.dataset.originalText) {
            if (el.dataset.translateHtml === 'true') {
                el.dataset.originalText = el.innerHTML;
            } else if (el.dataset.translateAttr) {
                el.dataset.originalText = el.getAttribute(el.dataset.translateAttr) || '';
            } else {
                el.dataset.originalText = el.textContent.trim();
            }
        }

        if (lang === 'fr') {
            if (el.dataset.translateHtml === 'true') {
                el.innerHTML = el.dataset.originalText;
            } else if (el.dataset.translateAttr) {
                el.setAttribute(el.dataset.translateAttr, el.dataset.originalText);
            } else {
                el.textContent = el.dataset.originalText;
            }
            return;
        }

        const translated = translations.en[key];
        if (!translated) {
            return;
        }

        if (el.dataset.translateHtml === 'true') {
            el.innerHTML = translated;
        } else if (el.dataset.translateAttr) {
            el.setAttribute(el.dataset.translateAttr, translated);
        } else {
            el.textContent = translated;
        }
    });
}

function createAccessibilityPanel() {
    /* bouton accessibilité */
    const toggle = document.createElement('button');
    toggle.className = 'accessibility-toggle';
    toggle.innerHTML = '⚙';
    toggle.setAttribute('aria-label', 'Ouvrir le panneau d\'accessibilité');
    toggle.setAttribute('aria-expanded', 'false');
    
    /* création du panneau */
    const panel = document.createElement('div');
    panel.className = 'accessibility-panel';
    panel.setAttribute('role', 'region');
    panel.setAttribute('aria-label', 'Paramètres d\'accessibilité');
    
    panel.innerHTML = `
        <h3>Accessibilité</h3>
        
        <div class="accessibility-section">
            <label>
                <input type="checkbox" id="highContrast" aria-label="Activer le contraste élevé">
                Contraste élevé
            </label>
        </div>
        
        <div class="font-size-control">
            <span class="font-size-label">Taille de texte :</span>
            <div class="font-size-buttons">
                <button class="size-btn active" data-size="normal" aria-label="Texte normal">Normal</button>
                <button class="size-btn" data-size="large" aria-label="Texte grand">Grand</button>
            </div>
        </div>
        
        <button class="reset-btn" aria-label="Réinitialiser les paramètres">Réinitialiser</button>
    `;
    
    document.body.appendChild(toggle);
    document.body.appendChild(panel);
    
    /* ajout du CSS */
    addAccessibilityStyles();
    
    /* événements */
    toggle.addEventListener('click', function() {
        panel.classList.toggle('visible');
        const isVisible = panel.classList.contains('visible');
        toggle.setAttribute('aria-expanded', isVisible);
    });
    
    /* contraste élevé */
    document.getElementById('highContrast').addEventListener('change', function() {
        document.body.classList.toggle('high-contrast');
        localStorage.setItem('highContrast', this.checked);
    });
    
    /* taille de texte */
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            if (this.dataset.size === 'large') {
                document.body.classList.add('large-text');
                localStorage.setItem('fontSize', 'large');
            } else {
                document.body.classList.remove('large-text');
                localStorage.setItem('fontSize', 'normal');
            }
        });
    });
    
    /* réinitialiser */
    document.querySelector('.reset-btn').addEventListener('click', function() {
        document.body.classList.remove('high-contrast', 'large-text');
        document.getElementById('highContrast').checked = false;
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('[data-size="normal"]').classList.add('active');
        localStorage.removeItem('highContrast');
        localStorage.removeItem('fontSize');
    });
}

function loadAccessibilitySettings() {
    /* charger contraste élevé */
    if (localStorage.getItem('highContrast') === 'true') {
        document.body.classList.add('high-contrast');
        document.getElementById('highContrast').checked = true;
    }

    /* charger taille de texte */
    const fontSize = localStorage.getItem('fontSize');
    if (fontSize === 'large') {
        document.body.classList.add('large-text');
        document.querySelector('[data-size="large"]').classList.add('active');
        document.querySelector('[data-size="normal"]').classList.remove('active');
    } else {
        document.querySelector('[data-size="normal"]').classList.add('active');
    }
}

function createScrollToTopButton() {
    /* bouton scroller en haut */
    const scrollBtn = document.createElement('button');
    scrollBtn.className = 'scroll-to-top';
    scrollBtn.innerHTML = '↑';
    scrollBtn.setAttribute('aria-label', 'Remonter en haut de la page');
    scrollBtn.style.display = 'block';
    
    document.body.appendChild(scrollBtn);
    
    /* fonction de scroll */
    scrollBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}    /* menu burger responsive */
function createResponsiveBurgerMenu() {
    const navElements = document.querySelectorAll('nav');

    navElements.forEach((nav, index) => {
        if (nav.querySelector('.nav-toggle')) {
            return;
        }

        const ul = nav.querySelector('ul');
        if (!ul) {
            return;
        }

        const menuId = `nav-menu-${index + 1}`;
        ul.id = menuId;

        const toggle = document.createElement('button');
        toggle.className = 'nav-toggle';
        toggle.type = 'button';
        toggle.setAttribute('aria-controls', menuId);
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Ouvrir le menu de navigation');
        toggle.innerHTML = '<span aria-hidden="true">☰</span><span class="sr-only">Menu</span>';

        toggle.addEventListener('click', function() {
            const isOpen = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen.toString());
        });

        nav.insertBefore(toggle, ul);
    });
}

function addAccessibilityStyles() {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'accessibility-panel.css';
    document.head.appendChild(link);
}

