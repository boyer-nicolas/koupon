'use client';
import React from 'react';
import { BiMoon, BiSun } from "react-icons/bi";
import Cookies from 'js-cookie';

export default class Theme extends React.Component
{
    constructor(props)
    {
        super(props);

        this.state = {
            theme: "light"
        };
    }

    componentDidMount()
    {
        const theme = Cookies.get('theme');
        if (theme === undefined)
        {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
            {
                Cookies.set('theme', 'dark');
                this.setState({ theme: "light" });
            }
            else
            {
                Cookies.set('theme', 'light');
                this.setState({ theme: "dark" });
            }
        }
        else
        {
            Cookies.get('theme') === "dark" ? this.setState({ theme: "light" }) : this.setState({ theme: "dark" });
        }
        document.querySelector('html').setAttribute('data-theme', theme);
    }


    handleThemeChange()
    {
        let availableThemes = ["light", "dark"];
        let currentTheme = document.querySelector('html').getAttribute('data-theme');
        let nextTheme = availableThemes[(availableThemes.indexOf(currentTheme) + 1) % availableThemes.length];

        Cookies.set('theme', nextTheme);

        this.setState({ theme: nextTheme === "dark" ? "light" : "dark" });

        document.querySelector('html').setAttribute('data-theme', nextTheme);
    }


    render()
    {
        return (
            <button className='flex' onClick={() => this.handleThemeChange()} title={"Switch to " + this.state.theme + " mode"}>
                <span className='lg:hidden'>
                    Switch to {this.state.theme} mode
                </span>
                {
                    this.state.theme === "light" ? <BiSun size={20} /> : <BiMoon size={20} />
                }
            </button>
        );
    }
}