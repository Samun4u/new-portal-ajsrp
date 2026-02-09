<style>
    :root {
        @if(getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR)
        --main-color: #007aff;
        --hover-color: #e5f0fb;
        --para-text: #5d697a;
        --header-color: #f2f4f7;
        --body-bg: #f9fafb;

        @else
         --main-color: {{getOption('main_color','#007aff')}};
        --hover-color: {{getOption('hover_color','#e5f0fb')}};
        --para-text: {{getOption('text_color','#5d697a')}};
        --header-color: {{getOption('header_color','#f2f4f7')}};
        --body-bg: {{getOption('bg_color','#f9fafb')}};
        --sidebar-bg: {{getOption('sidebar_bg','#e5f0fb')}};

        @endif

       --white: #ffffff;
        --white-8: rgba(255 255 255 / 8%);
        --white-50: rgba(255 255 255 / 50%);
        --black: #000000;
        --black-10: rgb(0 0 0 / 10%);
        --primary-color: #cdef84;
        --main-color-10: rgb(0 122 255 / 10%);
        --title-black: #01091a;
        --title-black-20: rgb(0 7 25 / 20%);
        --purple: #7a5af8;
        --purple-10: rgb(122 90 248 / 10%);
        --purple-20: rgb(122 90 248 / 20%);
        --stroke: #eef1f3;
        --green: #12b76a;
        --green-10: rgb(18 183 106 / 10%);
        --bg-color: #f9fafb;
        --yellow: #fd8900;
        --yellow-10: rgb(255 136 0 / 10%);
        --red: #ff3b30;
        --red-10: rgb(255 59 48 / 10%);
        --dark-color: #a5abba;
        --scroll-track: #efefef;
        --scroll-thumb: #dadada;
        --img-upload: #c4c4c4;
        --color1-10: rgb(253 76 0 / 10%);
        --color2-10: rgb(253 182 0 / 10%);
        --color3: #cfd9ec;
        --color4: #e8f3ff;
        --color5: #f0f3f6;
        --color6: #101827;
        --table-bottom-70: rgb(238 240 243 / 70%);
        --ld-testi-bg: #1a2438;
    }
</style>
