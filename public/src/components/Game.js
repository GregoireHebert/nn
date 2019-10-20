import React, { Component } from 'react';
import Sheep from './Sheep';
import Hud from './Hud';
import $ from 'jquery';

export default class Game extends Component {
  state = {
    lastActionTaken: null,
    life: null,
    hunger: null,
    playfulness: null,
    sleepiness: null
  };

  componentDidMount() {
      const that = this;

      that.getTamagotchi();
      setInterval(that.getTamagotchi, 15000);
  }

  getTamagotchi = () => {
      const that = this;
      $.get("http://localhost:8000/tamagotchi", function( tamagotchi ) {
          that.setState(() => ({
              life: tamagotchi.life,
              hunger: tamagotchi.hunger,
              playfulness: tamagotchi.playfulness,
              sleepiness: tamagotchi.sleepiness,
              lastActionTaken: tamagotchi.lastActionTaken
          }));
      }, 'json');
  }

  render () {
    return (
      <div id="world">
        <div id="sky">&nbsp;</div>
        <div id="grass_front">&nbsp;</div>
        <Hud
            life={this.state.life}
            hunger={this.state.hunger}
            playfulness={this.state.playfulness}
            sleepiness={this.state.sleepiness}
        />
        <Sheep lastActionTaken={this.state.lastActionTaken} />

        <div id="grass_back">&nbsp;</div>
        <div id="treeone">&nbsp;</div>
        <div id="treetwo">&nbsp;</div>
        <div id="rockone">&nbsp;</div>
        <div id="rocktwo">&nbsp;</div>
      </div>
    );
  }
}
