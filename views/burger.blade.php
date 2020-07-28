<button class="
    fj-burger 
    @if($bars == 3) fj-burger--3bars @endif
    {{ $class }}
    ">
    <span></span>
    <span></span>
    <span></span>
</button>

<x-style lang="scss">
    button.fj-burger {
        position:relative;
        z-index:300;
        width:40px;
        height:40px;
        background:white;
        border:none;
        position:relative;
        cursor:pointer;
        overflow:hidden;
        span {
            position:absolute;
            width:50%;
            height:2px;
            left:25%;
            top:50%;
            background:black;
            transition:all 0.3s;
            &:nth-child(1) {
                transform: translateY(-5px);
            }
            &:nth-child(2) {
                opacity:0;
            }
            &:nth-child(3) {
                transform: translateY(5px);
            }
        }
        &.fj-burger--open {
            span {
                &:nth-child(1) {
                    transform: translateY(0) rotate(45deg);
                }
                &:nth-child(2) {
                    transform: translateX(-40px);
                    opacity:0;
                }
                &:nth-child(3) {
                    transform: translateY(0) rotate(-45deg);
                }
            }
        }
        &.fj-burger--3bars {
            span {
                &:nth-child(2) {
                    opacity:1;
                }
            }
        }
    }
</x-style>

<x-script>
const fjBurger = document.querySelector('button.fj-burger');
const fjBurgerTarget = document.querySelector('#fj-burger-target');
fjBurger.addEventListener('click',function(){
    this.classList.toggle('fj-burger--open');
    fjBurgerTarget.classList.toggle('fj--visible');
});
</x-script>