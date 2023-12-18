import { useRouter, useRoute } from 'vue-router'

export default function useCustomAuth () {
  const authData = reactive({
    loggedIn: false,
    user: {}
  })

  function saveAuthDetails (response: object) {
    const user = response.data.user
    localStorage.setItem('currentUser', JSON.stringify(user))
    localStorage.setItem('isLoggedIn', 'loggedIn')
  }

  function getCurrentUser () {
    const user = localStorage.getItem('currentUser')
    if (user) {
      return JSON.parse(user)
    } else {
      return null
    }
  }

  function handleRedirection () {
    const router = useRouter()
    const route = useRoute()
    const currentUser = getCurrentUser()
    const isLoggedIn = localStorage.getItem('isLoggedIn')

    const role = currentUser.role.role
    const redirectUrl = `/${role}`

    if (isLoggedIn) {
      const location = window.location.pathname
      const locationRole = location.split('/')[1]
      if (locationRole !== role) {
        router.push(redirectUrl)
      }
    } else {
      router.push(redirectUrl)
    }
  }

  function checkIfAuthenticated () {
    const currentUser = getCurrentUser()
    if (!currentUser) {
      const router = useRouter()
      router.push('/login')
    } else {
      handleRedirection()
    }
  }

  function logout () {
    localStorage.removeItem('currentUser')
    localStorage.removeItem('isLoggedIn')
    alert('Logged out successfully')
    window.open('/login', '_SELF')
  }

  return {
    authData,
    saveAuthDetails,
    getCurrentUser,
    handleRedirection,
    checkIfAuthenticated,
    logout,
  }
}