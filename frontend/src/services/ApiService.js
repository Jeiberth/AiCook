import axios from 'axios'

class ApiService {
  constructor() {
    this.api = axios.create({
      baseURL: 'https://aicook-production.up.railway.app/api',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })

    // Add request interceptor for auth token
    this.api.interceptors.request.use(
      config => {
        const token = localStorage.getItem('token')
        if (token) {
          config.headers.Authorization = `Bearer ${token}`
        }
        return config
      },
      error => {
        return Promise.reject(error)
      }
    )

    // Add response interceptor for error handling
    // this.api.interceptors.response.use(
    //   response => response,
    //   error => {
    //     if (error.response?.status === 401) {
    //       localStorage.removeItem('token')
    //       window.location.href = '/login'
    //     }
    //     return Promise.reject(error)
    //   }
    // )
    this.api.interceptors.response.use(
        response => response,
        error => {
            const originalRequest = error.config

            // Skip handling 401s for login endpoint
            if (error.response?.status === 401 && originalRequest.url !== '/login') {
                localStorage.removeItem('token')
                window.location.href = '/login'
            }

            return Promise.reject(error)
        }
    )

  }

  setAuthToken(token) {
    if (token) {
      this.api.defaults.headers.Authorization = `Bearer ${token}`
    } else {
      delete this.api.defaults.headers.Authorization
    }
  }

  // Authentication
  async register(userData) {
    const response = await this.api.post('/register', userData)
    return response.data
  }

  async login(credentials) {
    const response = await this.api.post('/login', credentials)
    return response.data
  }

  async logout() {
    const response = await this.api.post('/logout')
    return response.data
  }

  // User management
  async updateUser(userData) {
    const response = await this.api.put('/user', userData)
    return response.data
  }

  // Recipe management
  async generateRecipes(searchParams) {
    const response = await this.api.post('/recipes/generate', searchParams)
    return response.data
  }

  async createRecipe(recipeData) {
    const response = await this.api.post('/recipes', recipeData)
    return response.data
  }

  async deleteRecipe(recipeId) {
    const response = await this.api.delete(`/recipes/${recipeId}`)
    return response.data
  }

  // Plan management
  async createPlan(planData) {
    const response = await this.api.post('/create/plan', planData)
    return response.data
  }

  async updatePlan(planId, planData) {
    const response = await this.api.put(`/plans/${planId}`, planData)
    return response.data
  }

  async deletePlan(planId) {
    const response = await this.api.delete(`/plans/${planId}`)
    return response.data
  }

  // Question management
  async askQuestion(questionData) {
    const response = await this.api.post('/questions', questionData)
    return response.data
  }
}

export default new ApiService()
