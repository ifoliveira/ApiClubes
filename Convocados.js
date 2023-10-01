import React, {useState, useEffect} from 'react';
import {Dimensions, TouchableScale,ImageBackground,KeyboardAvoidingView, Platform, Text, Vibration, View, Item, FlatList, SafeAreaView} from 'react-native';
import { Avatar,ListItem, ButtonGroup , Icon} from '@rneui/themed';
import { BG_IMAGE ,  API_TOKEN, BACKEND_URL} from '../../consts/Authconsts';
import useAuthContext from '../../hooks/useAuthcontext';
import MaterialCommunityIcons from 'react-native-vector-icons/MaterialCommunityIcons';
import { List } from 'react-native-paper';


function Convocados( {navigation, route} ) {
  const SCREEN_WIDTH = Dimensions.get('window').width;
  const SCREEN_HEIGHT = Dimensions.get('window').height;
  const [jugadoresequipo, setData] = useState([]);
  const [isLoading, setLoading] = useState(true);
  const {equipo, token} = useAuthContext();
  const [selectedIndex, setSelectedIndex] = useState(0,0);

  function changeButton(keySelect) {

    const newEleccion = eleccion.map((dia, i) => {

      if (i === keySelect) {
;
        return !dia;
      } else {
        // Return a new circle 50px below
        return dia;
        }
    });

    setEleccion(newEleccion);



  }

  const listaJugadores = async () => {
         try {

           const headers = { 'Authorization': `${API_TOKEN}${token}` };
           const response = await fetch(`${BACKEND_URL}api/equipo/${equipo}`, { headers });
           (response.status);
 
           switch(response.status) {
             case 200:
               const json = await response.json();
               (json)
               setData(JSON.parse(JSON.stringify(json)));
               break;
             case 401:
                 setError ("Acceso denegado usuario o contraseña inválida")
                 break;
       
             default:
                 setError ("Error indefinido contacte con el administrador")
             }
          
         } catch (error) {
           console.error(error);
         } finally {
           setLoading(false);
         }
       };
       
       useEffect(() => {
        listaJugadores();

      }, []);  
return (

<KeyboardAvoidingView 
  style={{  flexGrow: 1,
            width: '100%',
            height: SCREEN_HEIGHT,
            alignItems: 'center',
            justifyContent: 'space-around',
            backgroundColor: "red"
        }}
  behavior={Platform.OS === "ios" ? "padding" : "height"}
 >
  <ImageBackground source={BG_IMAGE} style={{flex: 1, top: 0, left: 0, width: '100%', height: SCREEN_HEIGHT,  alignItems: 'center'}}>

  {isLoading ? <Text>Cargando ......</Text> : 
  <>
        <SafeAreaView style={{backgroundColor:'rgba(0,0,0,0.6)',  width: '95%', borderRadius: 10, padding: 0, marginTop: 10}}>
        <FlatList
          data={jugadoresequipo.jugadores}
          renderItem={({item}) =>   

          <>
          <ListItem bottomDivider containerStyle={{backgroundColor: "rgba(0,0,0,0)" ,borderRadius: 0,borderColor: 'transparent', margin: 0 }}>
            <Avatar
              rounded
              source={ `${BACKEND_URL}storage/default/${item.foto}` ? { uri: `${BACKEND_URL}storage/default/${item.foto}` } : {} }
            />
            <ListItem.Content>
              <ListItem.Title style={{color: "white"}}>{item.nombre}, {item.apellidos} - {selectedIndex}</ListItem.Title>
              <ListItem.Subtitle>
              <ButtonGroup 
              buttonStyle={{padding: 0}}
              selectedButtonStyle={{ backgroundColor: "rgba(0,0,0,0.8)"}}
              buttonContainerStyle={{borderColor: 'transparent'}}
              containerStyle={{visible:false,  backgroundColor: "rgba(0,0,0,0)" ,borderRadius: 0,borderColor: 'transparent'  , height:30, width:100        }}
              color="rgba(0,0,0,0.1)"
              buttons={[
                <MaterialCommunityIcons  backgroundColor="rgba(0,0,0,0)" name="account-remove" size={20} color='red' /> ,
                <MaterialCommunityIcons name="account-plus" size={20} color='green' /> ,
                <MaterialCommunityIcons name="account-question" size={20} color='goldenrod' /> ,
              ]}
              selectedIndex={selectedIndex[0]}
              onPress={setSelectedIndex[i]}
            />       
      </ListItem.Subtitle>
            </ListItem.Content>
          </ListItem>
          
        </>}
          keyExtractor={item => item.id}
        />
        </SafeAreaView>
        
    </>   
  }
  </ImageBackground>
</KeyboardAvoidingView>

    );
  };

  

  
  

export default Convocados;

