#!/usr/bin/awk -f

BEGIN{
    srand();
    s=int(rand()*10);
} {
    c=128;
    w=127;
    f=0.1;
    for (i=1; i<=length($0); i+=1) {
        v=s+f*(i+2*NR);
        r=sin(v)*w+c;
        g=sin(v+2.094)*w+c;
        b=sin(v+4.188)*w+c;
        printf "\x1b[38;2;%d;%d;%dm%s", r, g, b, substr($0, i, 1);
    }
    printf "\x1b[0m\n";
}