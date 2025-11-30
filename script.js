const menuToggle = document.getElementById("menu-toggle")
const navMenu = document.getElementById("nav-menu")

menuToggle.addEventListener("click", ()=>{
    navMenu.classList.toggle("active")
})

navMenu.querySelector("a").forEach((link)=>{
    link.addEventListener("click",()=>{
        navMenu.classList.remove("active")
    })
})


document.querySelectorAll('a[href^="#"]').forEach((link)=>{
    link.addEventListener("click",function (e){
        const href = this.getAttribute("href")
        if (href !== "#"){
            e.preventDefault()
            const target = document.querySelector
            (href)
            if (target){
                target.scrollIntoView({
                    behavior: "smooth",
                    block:"start",
                })
            }
        }
    })
})

document.querySelectorAll(".skill-card").forEach((card) => {
    card.addEventListener("mouseenter",function() {
        this.style.boxShadow= "0 12px 24px rgba(99, 102, 241, 0.15)"
    })
    card.addEventListener("mouseleave", function() {
        this.style.boxShadow = "0 1px 3px rgba(0, 0, 0, 0.1)"
    })
})