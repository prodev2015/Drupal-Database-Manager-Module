

(function ($, Drupal, drupalSettings) {

  console.log("Drupal Settings:" + drupalSettings.basePath);
  console.log("Drums:" + drupalSettings['drums'][0]['model']);
  var model_url = drupalSettings['drums'][0]['model'];
  var BasePath = drupalSettings.basePath;
  var canvas = document.createElement('canvas');
  var ctx = canvas.getContext('2d');
  canvas.width = 2;
  canvas.height = 2;

  var getColorAsTextureURL = function(color) {
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, 2, 2);
    return canvas.toDataURL('image/png', 1.0);
  };

  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
      results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

  var iframe = document.getElementById('api-frame');
  var url = model_url;//getParameterByName(model_url);
  var prefix = getParameterByName('prefix');
  //var DEFAULT_URLID = 'c632823b6c204797bd9b95dbd9f53a06';
  var DEFAULT_URLID = '';//'4657516e60e541fab35f6fd76f31a44a';
  var DEFAULT_PREFIX = 'seat ';
  var CONFIG = {
    urlid: url !== '' ? url : DEFAULT_URLID,
    prefix: prefix !== '' ? prefix : DEFAULT_PREFIX
  };

  var Configurator = {
    api: null,
    config: null,
    options: [],
    textures: [],
    textureUids: [
      {
        diffuse:'',
        metalness:'',
        roughness:'',
      },
      {
        diffuse:'',
        metalness:'',
        roughness:'',
      },
      {
        diffuse:'',
        metalness:'',
        roughness:'',
      },
    ],
    materials: [],
    urls: [
      {
        diffuse:"http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/antiquecrimson_diffuse.png",
        metalness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/antiquecrimson_Metallic.png",
        roughness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/antiquecrimson_Roughness.png",
      },
      {
        diffuse:"http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/moderndry_diffuse.png",
        metalness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/moderndry_Metallic.png",
        roughness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/moderndry_Roughness.png",
      },
      {
        diffuse:"http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/nicotinewhite_diffuse.png",
        metalness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/nicotinewhite_Metallic.png",
        roughness: "http://s5e362f3f32c95lxamnbraw5.devcloud.acquia-sites.com/sites/custom/textures/nicotinewhite_Roughness.png",
      },
    ],
    /**
     * Initialize viewer
     */
    init: function (config, iframe) {
      this.config = config;
      var client = new Sketchfab(iframe);
      client.init(config.urlid, {
        ui_infos: 0,
        ui_controls: 0,
        graph_optimizer: 0,
        success: function onSuccess(api) {
          // Augment API with some helpers
          api = augment.extend(api, SketchfabViewerPlusMixin);
          window.api = api;
          api.start();

          api.addEventListener('camerastart', function() {
            console.log('Camera is moving');
          });

          api.addEventListener('viewerready', function () {
            this.api = api;



            api.getTextureList(function(err, textures) {
              if(!err) {
                //mytextureUid = textures[0].uid;
                console.log(textures);
                Configurator.textures = textures;

                Configurator.initializeOptions(function () {
                  console.log('Found the following options:', Configurator.options);
                  Configurator.selectOption(1);
                  UI.init(Configurator.config, Configurator.options);
                }.bind(Configurator));
              }
            });

          }.bind(this));
        }.bind(this),
        error: function onError() {
          console.log('Viewer error');
        },
        graph_optimizer: 1,
        ui_animations: 0,
        ui_annotations: 0,
        ui_general_controls: 0,
        ui_watermark_link: 0,
        ui_watermark: 0,
        ui_infos: 0,
        ui_stop: 0,
        transparent: 1,
        camera: 0,
        scrollwheel: 1,
        preload: 1,
        double_click: 0,
        orbit_constraint_pitch_down: 0,
        orbit_constraint_pitch_up: 0.65,
        //max_texture_size: 1024,
        orbit_constraint_pan: 1,
        orbit_constraint_zoom_out: 20
        //orbit_constraint_zoom_in: 5
      });
    },

    /**
     * Initialize options from scene
     */
    initializeOptions: function initializeOptions(callback) {
      Configurator.api.addTexture(Configurator.urls[0].diffuse, function(err, textureId) {
        Configurator.textureUids[0].diffuse = textureId;
      });

      Configurator.api.addTexture(Configurator.urls[0].metalness, function(err, textureId) {
        Configurator.textureUids[0].metalness = textureId;

      });

      Configurator.api.addTexture(Configurator.urls[0].roughness, function(err, textureId) {
        Configurator.textureUids[0].roughness = textureId;

      });

      Configurator.api.addTexture(Configurator.urls[1].diffuse, function(err, textureId) {
        Configurator.textureUids[1].diffuse = textureId;
      });

      Configurator.api.addTexture(Configurator.urls[1].metalness, function(err, textureId) {
        Configurator.textureUids[1].metalness = textureId;

      });

      Configurator.api.addTexture(Configurator.urls[1].roughness, function(err, textureId) {
        Configurator.textureUids[1].roughness = textureId;

      });

      Configurator.api.addTexture(Configurator.urls[2].diffuse, function(err, textureId) {
        Configurator.textureUids[2].diffuse = textureId;
      });

      Configurator.api.addTexture(Configurator.urls[2].metalness, function(err, textureId) {
        Configurator.textureUids[2].metalness = textureId;

      });

      Configurator.api.addTexture(Configurator.urls[2].roughness, function(err, textureId) {
        Configurator.textureUids[2].roughness = textureId;

      });

      Configurator.api.getMaterialsByName("moderndry", function(err, materials) {
        if (!err) {
          Configurator.materials = materials;
          console.log(materials);
        }
      });
      // this.api.getNodeMap(function (err, nodes) {
      //     if (err) {
      //         console.error(err);
      //         return;
      //     }
      //     var node;
      //     var isOptionObject = false;
      //     var keys = Object.keys(nodes);
      //     for (var i = 0; i < keys.length; i++) {
      //         node = nodes[keys[i]];
      //         isOptionObject = node.name &&
      //             node.name.indexOf(this.config.prefix) !== -1 &&
      //             (node.type === 'Geometry' || node.type === 'Group');
      //         if (isOptionObject) {
      //             this.options.push({
      //                 id: node.instanceID,
      //                 name: node.name,
      //                 selected: false
      //             });
      //         }
      //     }
      //     callback();
      // }.bind(this));
      this.options.push({
        id: 0,
        name: 'green',
        image: '1.png',
        selected: false
      });
      this.options.push({
        id: 1,
        name: 'red',
        image: '2.png',
        selected: false
      });
      this.options.push({
        id: 2,
        name: 'blue',
        image: '3.png',
        selected: false
      });

      callback();
    },

    /**
     * Select option to show
     */
    selectOption: function selectOption(index) {
      var options = this.options;

      if(Configurator.textureUids[index].diffuse.length > 0 && Configurator.materials.length > 0){
        //Diffuse
        Configurator.materials[0].channels['DiffuseColor'].texture = {
          uid: Configurator.textureUids[index].diffuse
        };
        //AlbedoPBR
        Configurator.materials[0].channels['AlbedoPBR'].texture = {
          uid: Configurator.textureUids[index].diffuse
        };
        //Matcap
        Configurator.materials[0].channels['Matcap'].texture = {
          uid: Configurator.textureUids[index].diffuse
        };

        //DiffusePBR
        Configurator.materials[0].channels['DiffusePBR'].texture = {
          uid: Configurator.textureUids[index].diffuse
        };

        //RoughnessPBR
        Configurator.materials[0].channels['RoughnessPBR'].texture = {
          uid: Configurator.textureUids[index].roughness
        };

        //MetalnessPBR
        Configurator.materials[0].channels['MetalnessPBR'].texture = {
          uid: Configurator.textureUids[index].metalness
        };

        //Emission
        // Configurator.materials[0].channels['EmitColor'].enable = true;
        Configurator.materials[0].channels['EmitColor'].factor = 0.0;
        Configurator.materials[0].channels['EmitColor'].texture = {
          uid: Configurator.textureUids[index].diffuse
        };
        Configurator.api.setMaterial(Configurator.materials[0]);
      }else
      {
        // Configurator.api.addTexture(Configurator.urls[index], function(err, textureId) {
        //     Configurator.textureUids[index] = textureId;
        //     Configurator.api.getMaterialsByName("moderndry", function(err, materials) {
        //         if (!err) {
        //             Configurator.materials = materials;
        //             console.log(materials);
        //             //Diffuse
        //             materials[0].channels['DiffuseColor'].texture = {
        //                 uid: textureId
        //             };
        //             //Emission
        //             materials[0].channels['EmitColor'].enable = true;
        //             materials[0].channels['EmitColor'].factor = 1;
        //             materials[0].channels['EmitColor'].texture = {
        //                 uid: textureId
        //             };
        //             Configurator.api.setMaterial(materials[0]);
        //         }
        //     });
        // });
      }
    }
  }

  var UI = {
    config: null,
    options: null,
    init: function init(config, options) {
      this.config = config;
      this.options = options;
      this.el = document.querySelector('.options');
      this.render();
      this.el.addEventListener('change', function (e) {
        e.preventDefault();
        var index = parseInt(this.el.elements['color'].value, 10);
        this.select(index);
      }.bind(this));
    },

    select: function (index) {
      Configurator.selectOption(parseInt(index, 10));
      this.render();
    },

    render: function () {
      if (this.config.urlid === DEFAULT_URLID) {
        this.renderRadio();
      } else {
        this.renderSelect();
      }
    },

    /**
     * Render options as multiple `<input type="radio">`
     */
    renderRadio: function render() {
      var html = this.options.map(function (option, i) {
        var checkedState = option.selected ? 'checked="checked"' : '';
        var className = option.name.replace(this.config.prefix, '');
        return [
          '<label class="options__option">',
          '<input type="radio" name="color" value="' + i + '" ' + checkedState + '>',
          '<img class="' + className + '"' + "src='" + BasePath + "/images/" + option.image + "'" +  '/>',
          '</label>'
        ].join('');
      }.bind(this)).join('');
      this.el.innerHTML = html;
    },

    /**
     * Render option as `<select>`
     */
    renderSelect: function () {
      var html = this.options.map(function (option, i) {
        var checkedState = option.selected ? 'selected="selected"' : '';
        return [
          '<option value="' + i + '" ' + checkedState + '>',
          option.name,
          '</option>',
        ].join('');
      }).join('');
      this.el.innerHTML = '<select name="color">' + html + '</select>';
    }
  }

  Configurator.init(CONFIG, iframe);
})(jQuery, Drupal, drupalSettings);
