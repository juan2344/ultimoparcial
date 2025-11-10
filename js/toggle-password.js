const passInput = document.getElementById('password')
const toggleBtn = document.getElementById('togglePass')
const eyeIcon = document.getElementById('eyeIcon')

toggleBtn.addEventListener('click', () => {
  const isHidden = passInput.type === 'password'
  passInput.type = isHidden ? 'text' : 'password'
  eyeIcon.className = isHidden
    ? 'ti ti-eye text-xl text-blue-600'
    : 'ti ti-eye-off text-xl text-gray-500'
})
