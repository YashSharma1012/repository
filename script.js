document.getElementById('year').textContent = new Date().getFullYear();

const navToggle = document.getElementById('navToggle');
const nav = document.getElementById('nav');
navToggle.addEventListener('click', () => {
  const isOpen = nav.classList.toggle('open');
  navToggle.setAttribute('aria-expanded', isOpen);
});
nav.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => nav.classList.remove('open'));
});

const form = document.getElementById('contactForm');
const note = document.getElementById('formNote');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  note.textContent = '';
  note.className = 'form-note';

  const data = new FormData(form);
  if (!data.get('name') || !data.get('email') || !data.get('message')) {
    note.textContent = 'Please fill in your name, email and message.';
    note.classList.add('error');
    return;
  }

  const submitBtn = form.querySelector('button[type="submit"]');
  submitBtn.disabled = true;
  submitBtn.textContent = 'Sending…';

  try {
    const res = await fetch(form.action, { method: 'POST', body: data });
    if (res.ok) {
      note.textContent = "Thanks — we'll be in touch shortly.";
      note.classList.add('success');
      form.reset();
    } else {
      throw new Error('Request failed');
    }
  } catch (err) {
    note.textContent = "Something went wrong sending that. Please try again in a moment.";
    note.classList.add('error');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Send message';
  }
});
