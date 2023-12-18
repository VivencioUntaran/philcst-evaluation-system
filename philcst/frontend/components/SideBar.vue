<template>
  <aside 
      class="sidebar transition"
      id="sidebar-component"
      :class="`${is_expanded && 'is-expanded'}`"
  >
    <button @click="toggleMenu">
      <span>
          <Icon 
            class="icon" 
            name="flat-color-icons:menu"
            width="20"
            height="30"
          />
      </span>
    </button>
    <ul class="sidebar__list">
      <li 
        class="sidebar__list-item" 
        v-for="(item, key) in getSideBarItems"
        :key="key"
      >
        <NuxtLink 
          :to="item.link" 
          class="item-link"
        >
          <Icon 
            :name="item.icon" 
            width="30" 
            height="40" 
            class="link-icon"
          />
          <span class="hide-link transition">
            {{ item.label }}
          </span>
        </NuxtLink>
      </li>

      <li class="sidebar__list-item">
        <nuxt-link 
          class="item-link"
          :to="profileLink"
        >
          <Icon 
            name="flat-color-icons:manager" 
            width="30" 
            height="40" 
            class="link-icon" 
          />
          <span class="hide-link transition">
            Profile
          </span>
        </nuxt-link>
      </li>

      <li class="sidebar__list-item">
        <div 
          class="item-link"
          @click="handleLogout"
        >
          <Icon 
            name="flat-color-icons:export" 
            width="30" 
            height="40" 
            class="link-icon" 
          />
          <span class="hide-link transition">
            Logout
          </span>
        </div>
      </li>
    </ul>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  userType: {
    type: String,
    default: 'admin',
  },
})

const { getCurrentUser } = useCustomAuth()

const is_expanded = ref(true)

const toggleMenu = () => {
  const pageContent = document.querySelector('.pageContent')
  const collapsedClass = 'pageContentCollapsed'
  is_expanded.value = !is_expanded.value
  
  

  if (!is_expanded.value) {
    pageContent.classList.add(collapsedClass)
  } else {
    pageContent.classList.remove(collapsedClass)
  }
}

const adminNavBarItems = [
  {
    label: "Dashboard",
    link: "/admin",
    icon: "flat-color-icons:businessman"
  },

  {
    label: "Academic Year",
    link: "/admin/academic-year",
    icon: "flat-color-icons:calendar"
  },

  {
    label: "Questionnaires",
    link: "/admin/questionnaires",
    icon: "flat-color-icons:document"
  },

  {
    label: "Dean",
    link: "/admin/dean",
    icon: "flat-color-icons:reading-ebook"
  },

  {
    label: "Evaluation Report",
    link: "/admin/evaluation-report",
    icon: "flat-color-icons:fine-print"
  }

]

const studentNavBarItems = [
  {
    label: "Dashboard",
    link: "/student",
    icon: "flat-color-icons:businessman"
  },
]

const facultyNavBarItems = [
  {
    label: "Dashboard",
    link: "/faculty",
    icon: "flat-color-icons:businessman"
  },
]

const deanNavBarItems = [
  {
    label: "Dashboard",
    link: "/dean",
    icon: "flat-color-icons:businessman"
  },

  {
    label: "Faculty",
    link: "/dean/faculty",
    icon: "flat-color-icons:reading-ebook"
  },
  {
    label: "Evaluation Report",
    link: "/dean/evaluation-report",
    icon: "flat-color-icons:fine-print"
  }

]

const getSideBarItems = computed(() => {
  const user = getCurrentUser()
  const role = user.role.role
  if(user.role.role === "admin"){
      return adminNavBarItems
  } else if ( role === "student") {
      return studentNavBarItems
  } else if (role === 'dean') {
    return deanNavBarItems
  } else if (role === 'faculty') {
    return facultyNavBarItems
  }
})

const { 
  logout
} = useCustomAuth()

function handleLogout () {
  if (confirm('Are you sure you want to log out?')) {
    logout()
  }
}

const profileLink = computed(() => {
  const user = getCurrentUser()
  return `/${user.role.role}/profile`
})

</script>

<style lang="scss" scoped>
@font-face {
  font-family: myfont;
  src: url(~/assets/inter.ttf);
}

$bg-accent: #964FA8;

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'myfont';
    font-weight: bold;
    text-decoration: none;
}

//  ==== NAV ====
.main-nav {
  height: 3rem;
  width: 100%;
  background-color: #e9e9e9;
  z-index: -1;

  h2 {
      font-size: 1.4rem;
      position: absolute;
      margin-top: .5em;
      left: 14.5rem;
  }
}

// ******

// ===== SIDEBAR-NAV BG=====
.sidebar {
  position: fixed;
  z-index: 999;
  top: 0;
  bottom: 0;
  min-height: 100%;
  padding-top: 1rem;
  background-color: $bg-accent;
  width: 13rem;
  box-shadow: 6px 0px 1px 0px #cba7d4;

  &__list-item {
    display: block;
    padding-left: 6px;
    margin-bottom: 1rem;
  }
  &--hidden {
    display: none;
  }

}

// ******

button {
  border: none;
  padding-right: 4px;
  border-radius: .2rem;
  margin-left: .7rem;
  margin-bottom: 1rem;
}


//  Minimize Side bar

.hide-link {
  display: inline-block;
  width: 100%;
}
.is-expanded {
  width: 3.1rem;

  .hide-link {
      height: 0;
      width: 0;
      overflow: hidden;
  }
}




.sidebar__list {
  list-style: none;
  line-height: 1.5;
  letter-spacing: 0.1rem;
  padding: 4px;
  text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.59);
}

.item-link {
  display: flex;
  align-items: center;
  cursor: pointer;
  transition: .2s ease-in-out;
}
.hide-link {
  color: white;
}

li:hover {
  background-color: #b674c9;
  border-radius: .50rem;
  text-shadow: none;
  // transition: 3ms ease-in ;

}

span {
  margin-left: 5px;
}
</style>